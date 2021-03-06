<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PointExport extends AbstractExport
{

    public function __construct($request)
    {
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->pointId = $request->point_id;
        $this->fileName = '点位数据';
    }

    public function collection()
    {
        $startClientdate = strtotime($this->startDate) * 1000;
        $endClientdate = strtotime($this->endDate) * 1000;

        $projectName = DB::connection('ar')
            ->table('xs_face_count_log as fcl')
            ->join('ar_product_list as apl', 'fcl.belong', '=', 'apl.versionname')
            ->whereRaw("fcl.clientdate between '$startClientdate' and '$endClientdate'")
            ->where('oid', '=', $this->pointId)
            ->selectRaw('apl.name')
            ->groupBy('belong')
            ->get();
        $projectNum = $projectName->count();
        $this->projectNum = $projectNum;

        $pName = $projectName->flatten()->all();

        $Max = "";
        $projectName->each(function ($item) use (&$Max) {
            $name = $item->name;
            $Max = $Max . ",max(case a.name when '$name' then concat_ws(',',cast(a.playernum7 as char),cast(a.playernum15 as char),cast(a.playernum21 as char), concat_ws('|',cast(a.omo_outnum as char),cast(a.omo_scannum as char)),cast(a.lovenum as char))else 0 end) '$name'";
        });

        $faceCount = DB::connection('ar')
            ->table('xs_face_count_log as fcl')
            ->join('ar_product_list as apl', 'fcl.belong', '=', 'apl.versionname')
            ->whereRaw("fcl.clientdate between '$startClientdate' and '$endClientdate' and oid='$this->pointId'")
            ->selectRaw("apl.name as name,date_format(fcl.date,'%Y-%m-%d') as date,sum(playernum7) as playernum7,sum(playernum15) as playernum15,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(lovenum) as lovenum")
            ->groupBy(DB::raw("belong,date_format(fcl.date,'%Y-%m-%d')"));

        $faceCount = DB::connection('ar')
            ->table(DB::raw("({$faceCount->toSql()}) as a"))
            ->selectRaw("a.date,sum(a.playernum7) as playernum7,sum(a.playernum15) as playernum15,sum(playernum21) as playernum21,concat_ws('|',sum(omo_outnum),sum(omo_scannum)) as omonum,sum(a.lovenum) as lovenum" . $Max)
            ->groupBy('a.date')
            ->get();

        $data = collect();
        $point = DB::connection('ar')
            ->table('avr_official as ao')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->where('ao.oid', '=', $this->pointId)
            ->selectRaw("aoa.name as areaName,aom.name as marketName,ao.name as pointName")
            ->first();
        $pointName = $point->areaName . '-' . $point->marketName . '-' . $point->pointName;
        $header1 = [$pointName, '合计', '', '', '', ''];
        for ($i = 0; $i < $projectNum; $i++) {
            $header1 = array_merge($header1, [$pName[$i]->name, '', '', '', '']);
        }
        $header2 = [''];
        for ($i = 0; $i < $projectNum + 1; $i++) {
            $header2 = array_merge($header2, ['', '', '', '', '']);
        }
        $header3 = [''];
        for ($i = 0; $i < $projectNum + 1; $i++) {
            $header3 = array_merge($header3, ['7″uCPE', '15″uCPE', '21″uCPE', 'CPA', 'CPL']);
        }
        $totalByDay = DB::connection('ar')
            ->table('xs_face_count_log as fcl')
            ->whereRaw("fcl.clientdate between '$startClientdate' and '$endClientdate'")
            ->where('oid', '=', $this->pointId)
            ->where('belong', '<>', 'all')
            ->groupBy('belong')
            ->selectRaw("sum(playernum7) as playernum7,sum(playernum15) as playernum15,sum(playernum21) as playernum21,concat_ws('|',sum(omo_outnum) ,sum(omo_scannum) ) as omonum ,sum(lovenum) as lovenum")
            ->get();
        $total = DB::connection('ar')
            ->table('xs_face_count_log as fcl')
            ->whereRaw("fcl.clientdate between '$startClientdate' and '$endClientdate'")
            ->where('oid', '=', $this->pointId)
            ->where('belong', '<>', 'all')
            ->selectRaw("sum(playernum7) as playernum7,sum(playernum15) as playernum15,sum(playernum21) as playernum21,concat_ws('|',sum(omo_outnum) ,sum(omo_scannum) ) as omonum,sum(lovenum) as lovenum")
            ->get();
        $totalNum = json_decode(json_encode($total), true);
        $totalNum = collect($totalNum)->flatten()->all();

        $totalByDayNum = json_decode(json_encode($totalByDay), true);
        $totalByDayNum = collect($totalByDayNum)->flatten()->all();

        $header4 = ['Total'];
        $header4 = array_merge($header4, $totalNum);
        $header4 = array_merge($header4, $totalByDayNum);

        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
        $data->push($header4);
        $faceCount->each(function ($item) use (&$data) {
            $aa = [];
            foreach ($item as $key => $value) {
                if ($key == 'date' || $key == 'playernum7' || $key == 'playernum15' || $key == 'playernum21' || $key == 'omonum' || $key == 'lovenum') {
                    $aa[$key] = $value;
                } else {
                    if ($value == 0) {
                        $aa[$key . '-' . 'playernum7'] = 0;
                        $aa[$key . '-' . 'playernum15'] = 0;
                        $aa[$key . '-' . 'playernum21'] = 0;
                        $aa[$key . '-' . 'omonum'] = 0 . '|' . 0;
                        $aa[$key . '-' . 'lovenum'] = 0;
                    } else {
                        $num = explode(',', $value);
                        $aa[$key . '-' . 'playernum7'] = $num[0];
                        $aa[$key . '-' . 'playernum15'] = $num[1];
                        $aa[$key . '-' . 'playernum21'] = $num[2];
                        $aa[$key . '-' . 'omonum'] = $num[3];
                        $aa[$key . '-' . 'lovenum'] = $num[4];
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
                $cellArray = ['A1:A3'];
                for ($i = 0; $i < $this->projectNum + 1; $i++) {
                    $startNum = 1 + 5 * $i;
                    $endNum = 5 * ($i + 1);

                    $cellArray[] = $this->change($startNum) . '1:' . $this->change($endNum) . '2';
                }
                $event->sheet->getDelegate()->setMergeCells($cellArray);

                $event->sheet->getDelegate()->getStyle('A1:' . $this->change(($this->projectNum + 1) * 5) . $this->data->count())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ]
                ]);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change(($this->projectNum + 1) * 5) . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change(($this->projectNum + 1) * 5) . '3')
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