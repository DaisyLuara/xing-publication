<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/6/6
 * Time: 上午11:30
 */

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


class ShortUrlExport extends AbstractExport
{
    private $id;
    private $startDate;
    private $endDate;
    private $dataType;

    public function __construct($request)
    {
        $this->fileName = '外链数据';
        $this->id = $request->id;
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->dataType = $request->data_type;
    }

    public function collection()
    {
        if (!$this->id) {
            abort(500, '请选择外链');
        }
        if (!$this->dataType) {
            abort(500, '请选择下载类型');
        }

        $startClientdate = strtotime($this->startDate . ' 00:00:00') * 1000;
        $endClientdate = strtotime($this->endDate . ' 23:59:59') * 1000;
        $header = [];
        $count = [];
        if ($this->dataType === 'times') {
            $count = DB::table('short_url_records')
                ->where('short_url_id', $this->id)
                ->whereRaw("clientdate between '$startClientdate' and '$endClientdate' and utm_source not in (16, 19, 30, 31, 177, 182, 327, 328, 329, 334, 335, 540)")
                ->groupBy('date')
                ->selectRaw("date_format(created_at,'%Y-%m-%d') as date ,count(*) as times")
                ->get()
                ->toArray();
            $header = ['日期', '人次'];
        }

        if ($this->dataType === 'num') {
            $sql = DB::table('short_url_records')
                ->whereRaw("short_url_id='$this->id' and clientdate between '$startClientdate' and '$endClientdate' and utm_source not in (16, 19, 30, 31, 177, 182, 327, 328, 329, 334, 335, 540)")
                ->groupBy(DB::raw("ip,date_format(created_at,'%Y-%m-%d')"))
                ->selectRaw("date_format(created_at,'%Y-%m-%d') as date");
            $count = DB::table(DB::raw("({$sql->toSql()}) a"))
                ->groupBy('date')
                ->selectRaw('date,count(*) as num')
                ->get()->toArray();
            $header = ['日期', '人数'];
        }
        array_unshift($count, $header);
        $data = collect($count);
        $this->data = $data;
        return $data;

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getDelegate()
                    ->getStyle('A1:B' . $this->data->count())
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A1:B' . $this->data->count())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ]
                    ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:B1')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                $event->sheet->getDelegate()->freezePane('A2');
            }
        ];
    }

}