<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/15
 * Time: 下午5:52
 */

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


class PlayTimesExport extends AbstractExport
{
    public function __construct($request)
    {
        $this->fileName = '人次数据';
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
    }

    public function collection()
    {
        $data = collect();
        $header1 = ['点位ID', '点位名称', '7″人次', '15″人次', '21″人次', '时间'];
        $header2 = ['', '', '', '', '', ''];
        $data->push($header1);
        $data->push($header2);

        $startClientdate = strtotime($this->startDate) * 1000;
        $endClientdate = strtotime($this->endDate) * 1000;
        $count = DB::connection('ar')->table('xs_face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_market as aom', 'aom.marketid', '=', 'ao.marketid')
            ->join('avr_official_area as aoa', 'aoa.areaid', '=', 'ao.areaid')
            ->whereRaw("fcl.clientdate between '$startClientdate' and '$endClientdate' and fcl.belong='all'")
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 177, 182, 327, 328, 329, 334, 335, 540])
            ->groupBy('fcl.oid')
            ->orderBy('aoa.areaid', 'desc')
            ->orderBy('aom.marketid', 'desc')
            ->orderBy('ao.oid', 'desc')
            ->selectRaw('ao.oid,aoa.name as areaname,aom.name as marketname,ao.name as pointname,sum(playtimes7) as playtimes7,sum(playtimes15) as playtimes15,sum(playtimes21) as playtimes21')
            ->get();
        foreach ($count as $item) {
            $arr = [
                'id' => $item->oid,
                'name' => $item->areaname . ' ' . $item->marketname . ' ' . $item->pointname,
                'playtimes7' => $item->playtimes7,
                'playtimes15' => $item->playtimes15,
                'playtimes21' => $item->playtimes21,
                'date' => $this->startDate . '|' . $this->endDate
            ];
            $data->push($arr);
        }
        $this->data = $data;
        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellArray = [
                    'A1:A2', 'B1:B2', 'C1:C2', 'D1:D2', 'E1:E2', 'F1:F2'
                ];

                //合并单元格
                $event->getDelegate()->setMergeCells($cellArray);

                //黑线框
                $event->sheet->getDelegate()->getStyle('A1:F' . $this->data->count())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ]
                ]);

                //水平居中 垂直居中
                $event->sheet->getDelegate()
                    ->getStyle('A1:F' . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                //表头加粗
                $event->sheet->getDelegate()
                    ->getStyle('A1:F2')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);

                //冻结表头
                $event->sheet->getDelegate()->freezePane('A3');
            }
        ];
    }
}