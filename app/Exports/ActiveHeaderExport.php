<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/12
 * Time: 15:47
 */

namespace App\Exports;

use function foo\func;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithEvents;
use DB;

class ActiveHeaderExport implements FromCollection, WithStrictNullComparison, WithEvents
{
    public function collection()
    {
        $projectName = DB::connection('ar')
            ->table('face_count_log as fcl')
            ->join('ar_product_list as apl', 'fcl.belong', '=', 'apl.versionname')
            ->whereRaw("date_format(fcl.date,'%Y-%m') between '2018-04' and '2018-04'")
            ->where('oid', '=', '30')
            ->where('belong', '<>', 'all')
            ->selectRaw('apl.name')
            ->groupBy('belong')
            ->get();
        $projectNum = $projectName->count();

        $aaa = json_decode(json_encode($projectName), true);
        $pName = collect($aaa)->flatten()->all();

        $Max = "";
        $projectName->each(function ($item) use (&$Max) {
            $item = json_decode(json_encode($item), true);
            $name = $item['name'];
            $Max = $Max . ",max(case a.name when '$name' then concat_ws(',', cast(a.lookNum as char), cast(a.playerNum as char), cast(a.loveNum as char),cast(a.outNum as char), cast(a.scanNum as char))else 0 end) '$name'";
        });

        $faceCount = DB::connection('ar')
            ->table('face_count_log as fcl')
            ->join('ar_product_list as apl', 'fcl.belong', '=', 'apl.versionname')
            ->whereRaw("date_format(fcl.date,'%Y-%m') between '2018-04' and '2018-04' and oid='30' and belong <> 'all' ")
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
        $data->push($header1);
        $data->push($header2);
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
        return [];
    }

    public function change($x)
    {
        $a = intval(infloor($x / 26));
        $b=intval(floor($x/26));
        return $this->change($a - 1) +(char)($b + 'A');
    }

}