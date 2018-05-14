<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PointExport implements FromCollection, WithStrictNullComparison, WithEvents
{

    public function __construct($request)
    {
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
        $this->oid = $request->oid;
    }

    public function collection()
    {
        $projectName = DB::connection('ar')
            ->table('face_count_log as fcl')
            ->join('ar_product_list as apl', 'fcl.belong', '=', 'apl.versionname')
            ->whereRaw("date_format(fcl.date,'%Y-%m-%d') between '$this->startDate' and '$this->endDate'")
            ->where('oid', '=', $this->oid)
            ->where('belong', '<>', 'all')
            ->selectRaw('apl.name')
            ->groupBy('belong')
            ->get();
        $projectNum = $projectName->count();
        $this->projectNum = $projectNum;

        $aaa = json_decode(json_encode($projectName), true);
        $pName = collect($aaa)->flatten()->all();

        $Max = "";
        $projectName->each(function ($item) use (&$Max) {
            $item = json_decode(json_encode($item), true);
            $name = $item['name'];
            $Max = $Max . ",max(case a.name when '$name' then concat_ws(',', cast(a.lookNum as char), cast(a.playerNum as char),cast(a.outNum as char), cast(a.scanNum as char),cast(a.loveNum as char))else 0 end) '$name'";
        });

        $faceCount = DB::connection('ar')
            ->table('face_count_log as fcl')
            ->join('ar_product_list as apl', 'fcl.belong', '=', 'apl.versionname')
            ->whereRaw("date_format(fcl.date,'%Y-%m-%d') between '$this->startDate' and '$this->endDate' and oid='$this->oid' and belong <> 'all' ")
            ->selectRaw("apl.name as name,date_format(fcl.date,'%Y-%m-%d') as date,sum(looknum) as lookNum,sum(playernum) as playerNum,sum(outnum) as outNum,sum(scannum) as scanNum,sum(lovenum) as loveNum")
            ->groupBy(DB::raw("belong,date_format(fcl.date,'%Y-%m-%d')"));

        $faceCount = DB::connection('ar')
            ->table(DB::raw("({$faceCount->toSql()}) as a"))
            ->selectRaw("a.date" . $Max)
            ->groupBy('a.date')
            ->get();

        $data = collect();
        $header1 = [''];
        for ($i = 0; $i < $projectNum; $i++) {
            $header1 = array_merge($header1, [$pName[$i], '', '', '', '']);
        }
        $header2 = [''];
        for ($i = 0; $i < $projectNum; $i++) {
            $header2 = array_merge($header2, ['', '', '', '', '']);
        }
        $header3 = [''];
        for ($i = 0; $i < $projectNum; $i++) {
            $header3 = array_merge($header3, ['围观', '玩家', '生成', '扫码', '会员']);
        }
        $total = DB::connection('ar')
            ->table('face_count_log as fcl')
            ->whereRaw("date_format(fcl.date,'%Y-%m-%d') between '$this->startDate' and '$this->endDate'")
            ->where('oid', '=', $this->oid)
            ->where('belong', '<>', 'all')
            ->groupBy('belong')
            ->selectRaw("sum(looknum) as lookNum,sum(playernum) as playerNum,sum(outnum) as outNum,sum(scannum) as scanNum,sum(lovenum) as loveNum")
            ->get();
        $totalNum = json_decode(json_encode($total), true);
        $totalNum = collect($totalNum)->flatten()->all();
        $header4 = array_merge(['Total'], $totalNum);
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
        $data->push($header4);
        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);

            $aa = [];
            foreach ($item as $key => $value) {
                if ($key == 'date') {
                    $aa['date'] = $value;
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
                $cellArray = ['A1:A3'];
                for ($i = 0; $i < $this->projectNum; $i++) {
                    $startNum = 1 + 5 * $i;
                    $endNum = 5 * ($i + 1);

                    $cellArray[] = $this->change($startNum) . '1:' . $this->change($endNum) . '2';
                }
                $event->sheet->getDelegate()->getStyle('A1:' . $this->change($this->projectNum * 5) . $this->data->count())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '00000000']
                        ]
                    ]
                ]);
                $event->sheet->getDelegate()->setMergeCells($cellArray);
                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $this->change($this->projectNum * 5) . $this->data->count())
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
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