<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;
use DB;

class ProjectExport extends AbstractExport
{
    public function __construct($request)
    {
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
        $this->fileName = '节目数据';
    }

    public function collection()
    {
        $startDate = $this->start_date;
        $endDate = $this->end_date;
        $dates = [$startDate];
        while ((new Carbon($endDate))->gt(new Carbon($startDate))) {
            $startDate = date_format((new Carbon($startDate))->addMonth(1), 'Y-m');
            $dates[] = $startDate;
        }

        $this->dateNum = sizeof($dates);
        $faceCount = DB::connection('ar')->table('face_count_log')
            ->join('ar_product_list', 'belong', '=', 'versionname')
            ->join('avr_official', 'face_count_log.oid', '=', 'avr_official.oid')
            ->join('avr_official_area', 'avr_official.areaid', '=', 'avr_official_area.areaid')
            ->join('avr_official_market', 'avr_official.marketid', '=', 'avr_official_market.marketid')
            ->whereRaw("date_format(face_count_log.date, '%Y-%m') BETWEEN '$this->start_date' AND '$this->end_date' and avr_official.oid not in ('16', '19', '30', '31', '177','182','327','328','329','334','335')")
            ->groupby(DB::raw("belong,date_format(face_count_log.date,'%Y-%m')"))
            ->selectRaw("ar_product_list.name as projectName,date_format(face_count_log.date,'%Y-%m') as date, sum(looknum) as lookNum ,sum(playernum) as playerNum ,sum(lovenum) as loveNum,sum(outnum) as outNum,sum(scannum) as scanNum");

        $Max = "";
        for ($i = 0; $i < sizeof($dates); $i++) {
            $Max = $Max . ",max(case a.date when '$dates[$i]' then concat_ws(',',cast(a.lookNum as char),cast(a.playerNum as char),cast(a.loveNum as char),cast(a.outNum as char),cast(a.scanNum as char)) else 0 end) '$dates[$i]'";
        }

        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount->toSql()}) as a"))
            ->selectRaw("a.projectName " . $Max)
            ->groupBy(DB::raw('a.projectName'))
            ->get();

        $data = collect();
        $header1 = ['节目名称'];
        for ($i = 0; $i < sizeof($dates); $i++) {
            $header1 = array_merge($header1, [$dates[$i], '', '', '', '']);
        }
        $header2 = [''];
        for ($i = 0; $i < sizeof($dates); $i++) {
            $header2 = array_merge($header2, ['', '', '', '', '']);
        }
        $header3 = [''];
        for ($i = 0; $i < sizeof($dates); $i++) {
            $header3 = array_merge($header3, ['围观数', '玩家数', '会员数', '生成数', '扫码数']);
        }
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
        $faceCount->each(function ($item) use (&$data) {
            $aa = [];
            foreach ($item as $key => $value) {

                if ($key == 'projectName') {
                    $aa['projectName'] = $value;
                } else {
                    if ($value == 0) {
                        $aa[$key . '-' . 'lookNum'] = 0;
                        $aa[$key . '-' . 'playerNum'] = 0;
                        $aa[$key . '-' . 'loveNum'] = 0;
                        $aa[$key . '-' . 'outNum'] = 0;
                        $aa[$key . '-' . 'scanNum'] = 0;
                    } else {
                        $num = explode(',', $value);
                        $aa[$key . '-' . 'lookNum'] = $num['0'];
                        $aa[$key . '-' . 'playerNum'] = $num['1'];
                        $aa[$key . '-' . 'loveNum'] = $num['2'];
                        $aa[$key . '-' . 'outNum'] = $num['3'];
                        $aa[$key . '-' . 'scanNum'] = $num['4'];
                    }
                }
            }
            $data->push($aa);
        });
        $this->data = $data;
        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:' . $this->change($this->dateNum * 5) . $this->data->count())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ]
                ]);

                $cellArray = ['A1:A3'];
                for ($i = 0; $i < $this->dateNum; $i++) {
                    $startNum = 1 + 5 * $i;
                    $endNum = 5 * ($i + 1);
                    $cellArray[] = $this->change($startNum) . '1:' . $this->change($endNum) . '2';
                }

                $event->sheet->getDelegate()->setMergeCells($cellArray);
                //居中
                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change($this->dateNum * 5) . $this->data->count())
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                //表头加粗
                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change($this->dateNum * 5) . '3')
                    ->applyFromArray([
                        'font' => [
                            'bold' => 'true'
                        ]
                    ]);
                $event->sheet->getDelegate()->freezePane('B4');
            }
        ];
    }

    public function change($x)
    {
        $map = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $t = "";
        while ($x >= 0) {
            $t = $map[$x % 26] . $t;
            $x = floor($x / 26) - 1;
        }
        return $t;
    }
}