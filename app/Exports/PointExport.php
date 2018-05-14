<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;

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
            $header1[] = $pName[$i];
            $header1[] = '';
            $header1[] = '';
            $header1[] = '';
            $header1[] = '';
        }
        $header2 = [''];
        for ($i = 0; $i < $projectNum; $i++) {
            $header2[] = '围观';
            $header2[] = '玩家';
            $header2[] = '生成';
            $header2[] = '扫码';
            $header2[] = '会员';
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
        $header3 = array_merge(['Total'], $totalNum);
        $data->push($header1);
        $data->push($header2);
        $data->push($header3);
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

        return $data;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellArray = ['A1:A2'];
                for ($i = 0; $i < $this->projectNum; $i++) {
                    $startNum = 1 + 5 * $i;
                    $endNum = 5 * ($i + 1);

                    $cellArray[] = $this->change($startNum) . '1:' . $this->change($endNum) . '1';
                }
                $event->sheet->getDelegate()->setMergeCells($cellArray);
            }
        ];
    }


    //暂时只支持135个节目
    public function change($num)
    {
        $map = [
            '0' => 'A',
            '1' => 'B',
            '2' => 'C',
            '3' => 'D',
            '4' => 'E',
            '5' => 'F',
            '6' => 'G',
            '7' => 'H',
            '8' => 'I',
            '9' => 'J',
            '10' => 'K',
            '11' => 'L',
            '12' => 'M',
            '13' => 'N',
            '14' => 'O',
            '15' => 'P',
            '16' => 'Q',
            '17' => 'R',
            '18' => 'S',
            '19' => 'T',
            '20' => 'U',
            '21' => 'V',
            '22' => 'W',
            '23' => 'X',
            '24' => 'Y',
            '25' => 'Z',

        ];

        if ($num < 26) {
            foreach ($map as $key => $value) {
                if ($key == $num) {
                    return $value;
                }
            }
        }

        if ($num >= 26) {
            $a = floor($num / 26) - 1;
            $b = $num % 26;
            $letter = "";
            foreach ($map as $key => $value) {
                if ($key == $a) {
                    $letter = $letter . $value;
                }
            }
            foreach ($map as $key => $value) {
                if ($key == $b) {
                    $letter = $letter . $value;
                }
            }
            return $letter;
        }
    }

}