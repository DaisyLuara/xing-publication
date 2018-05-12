<?php

namespace App\Exports;


use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;

class PointExport implements FromCollection, WithStrictNullComparison, WithEvents
{
    public function __construct($request)
    {
        $this->oid = $request->oid;
        $this->date = $request->date;

    }

    public function collection()
    {
        $date = (new Carbon($this->date))->toDateString();

        //week1
        $startDate1 = $date;
        $endDate1 = (new Carbon($startDate1))->addDay(6)->toDateString();
        //week2
        $startDate2 = (new Carbon($date))->addDay(7)->toDateString();
        $endDate2 = (new Carbon($startDate2))->addDay(6)->toDateString();
        //week3
        $startDate3 = (new Carbon($date))->addDay(14)->toDateString();
        $endDate3 = (new Carbon($startDate3))->addDay(6)->toDateString();
        //week4
        $startDate4 = (new Carbon($date))->addDay(21)->toDateString();
        $endDate4 = (new Carbon($this->date))->endOfMonth()->toDateString();


        $sql1 = "when date_format(face_count_log.date, '%Y-%m-%d') between '{$startDate1}' and '{$endDate1}' then 'week1'";
        $sql2 = "when date_format(face_count_log.date, '%Y-%m-%d') between '{$startDate2}' and '{$endDate2}' then 'week2'";
        $sql3 = "when date_format(face_count_log.date, '%Y-%m-%d') between '{$startDate3}' and '{$endDate3}' then 'week3'";
        $sql4 = "when date_format(face_count_log.date, '%Y-%m-%d') between '{$startDate4}' and '{$endDate4}' then 'week4'";
        $sql = $sql1 . $sql2 . $sql3 . $sql4;

        $sumSql = "sum(looknum) as lookNum,sum(playernum) as playerNum,sum(lovenum) as loveNum, sum(outnum) as outNum,sum(scanNum) as scanNum";
        $faceCount = DB::connection('ar')->table('face_count_log')
            ->join('ar_product_list', 'face_count_log.belong', '=', 'ar_product_list.versionname')
            ->whereRaw("date_format(face_count_log.date, '%Y-%m') between '{$this->date}' and '{$this->date}' and oid='$this->oid' and belong <> 'all' ")
            ->groupBy(DB::raw("belong,week"))
            ->selectRaw("ar_product_list.name as name,case " . $sql . "else 0 end as week ," . $sumSql);


        $Max1 = ",max(case a.week when 'week1' then concat_ws(',', cast(a.lookNum as char), cast(a.playerNum as char), cast(a.loveNum as char),cast(a.outNum as char), cast(a.scanNum as char))else 0 end) 'week1'";
        $Max2 = ",max(case a.week when 'week2' then concat_ws(',', cast(a.lookNum as char), cast(a.playerNum as char), cast(a.loveNum as char),cast(a.outNum as char), cast(a.scanNum as char))else 0 end) 'week2'";
        $Max3 = ",max(case a.week when 'week3' then concat_ws(',', cast(a.lookNum as char), cast(a.playerNum as char), cast(a.loveNum as char),cast(a.outNum as char), cast(a.scanNum as char))else 0 end) 'week3'";
        $Max4 = ",max(case a.week when 'week4' then concat_ws(',', cast(a.lookNum as char), cast(a.playerNum as char), cast(a.loveNum as char),cast(a.outNum as char), cast(a.scanNum as char))else 0 end) 'week4'";
        $Max = $Max1 . $Max2 . $Max3 . $Max4;
        $faceCount = DB::connection('ar')
            ->table(DB::raw("({$faceCount->toSql()}) as a"))
            ->selectRaw("a.name as projectName" . $Max)
            ->groupBy(DB::raw('a.name'))
            ->get();

        $data = collect();
        $header1 = ['', '第一周', '', '', '', '', '第二周', '', '', '', '', '第三周', '', '', '', '', '第四周', '', '', '', ''];
        $header2 = ['', '围观数', '玩家数', '会员数', '生成数', '扫码数', '围观数', '玩家数', '会员数', '生成数', '扫码数', '围观数', '玩家数', '会员数', '生成数', '扫码数', '围观数', '玩家数', '会员数', '生成数', '扫码数'];
        $data->push($header1);
        $data->push($header2);

        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);

            $aa = [];
            foreach ($item as $key => $value) {
                if ($key == 'projectName') {
                    $aa['projectName'] = $value;
                } else {
                    $num = explode(',', $value);
                    $aa[$key . '-' . 'lookNum'] = $num['0'];
                    $aa[$key . '-' . 'playerNum'] = $num['1'];
                    $aa[$key . '-' . 'loveNum'] = $num['2'];
                    $aa[$key . '-' . 'outNum'] = $num['3'];
                    $aa[$key . '-' . 'scanNum'] = $num['4'];
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
                $event->sheet->getDelegate()->setMergeCells(['A1:A2','B1:F1','G1:K1','L1:P1','Q1:U1']);
            }
        ];
    }
}