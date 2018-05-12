<?php

namespace App\Exports;

use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;

class ProjectExport implements FromCollection, WithStrictNullComparison, WithEvents
{
    public function collection()
    {
        $date1 = date_format(Carbon::now()->addMonth(-5), 'Y-m');
        $date2 = date_format(Carbon::now()->addMonth(-4), 'Y-m');
        $date3 = date_format(Carbon::now()->addMonth(-3), 'Y-m');
        $date4 = date_format(Carbon::now()->addMonth(-2), 'Y-m');
        $date5 = date_format(Carbon::now()->addMonth(-1), 'Y-m');
        $date6 = date_format(Carbon::now(), 'Y-m');

        $faceCount = DB::connection('ar')->table('face_count_log')
            ->join('ar_product_list', 'belong', '=', 'versionname')
            ->join('avr_official', 'face_count_log.oid', '=', 'avr_official.oid')
            ->join('avr_official_area', 'avr_official.areaid', '=', 'avr_official_area.areaid')
            ->join('avr_official_market', 'avr_official.marketid', '=', 'avr_official_market.marketid')
            ->whereRaw("date_format(face_count_log.date, '%Y-%m') BETWEEN '$date1' AND '$date6' and avr_official.oid not in ('16', '19', '30', '31', '335', '334', '329', '328', '327')")
            ->groupby(DB::raw("belong,date_format(face_count_log.date,'%Y-%m')"))
            ->selectRaw("ar_product_list.name as projectName,date_format(face_count_log.date,'%Y-%m') as date, sum(looknum) as lookNum ,sum(playernum) as playerNum ,sum(lovenum) as loveNum,sum(outnum) as outNum,sum(scannum) as scanNum");

        $sql1 = "a.projectName ,max(case a.date when '$date1' then concat_ws(',',cast(a.looknum as char),cast(a.playernum as char),cast(a.lovenum as char),cast(a.outnum as char),cast(a.scannum as char)) else 0 end) '$date1'";
        $sql2 = ",max(case a.date when '$date2' then concat_ws(',',cast(a.looknum as char),cast(a.playernum as char),cast(a.lovenum as char),cast(a.outnum as char),cast(a.scannum as char)) else 0 end) '$date2'";
        $sql3 = ",max(case a.date when '$date3' then concat_ws(',',cast(a.looknum as char),cast(a.playernum as char),cast(a.lovenum as char),cast(a.outnum as char),cast(a.scannum as char)) else 0 end) '$date3'";
        $sql4 = ",max(case a.date when '$date4' then concat_ws(',',cast(a.looknum as char),cast(a.playernum as char),cast(a.lovenum as char),cast(a.outnum as char),cast(a.scannum as char)) else 0 end) '$date4'";
        $sql5 = ",max(case a.date when '$date5' then concat_ws(',',cast(a.looknum as char),cast(a.playernum as char),cast(a.lovenum as char),cast(a.outnum as char),cast(a.scannum as char)) else 0 end) '$date5'";
        $sql6 = ",max(case a.date when '$date6' then concat_ws(',',cast(a.looknum as char),cast(a.playernum as char),cast(a.lovenum as char),cast(a.outnum as char),cast(a.scannum as char)) else 0 end) '$date6'";
        $sql = $sql1 . $sql2 . $sql3 . $sql4 . $sql5 . $sql6;
        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount->toSql()}) as a"))
            ->selectRaw("$sql")
            ->groupBy(DB::raw('a.projectName'))
            ->get();

        $data = collect();
        $header1 = ['节目', $date1, '', '', '', '', $date2, '', '', '', '', $date3, '', '', '', '', $date4, '', '', '', '', $date5, '', '', '', '', $date6, '', '', '', ''];
        $header2 = ['', '围观数', '玩家数', '会员数', '生成数', '扫码数', '围观数', '玩家数', '会员数', '生成数', '扫码数', '围观数', '玩家数', '会员数', '生成数', '扫码数', '围观数', '玩家数', '会员数', '生成数', '扫码数', '围观数', '玩家数', '会员数', '生成数', '扫码数', '围观数', '玩家数', '会员数', '生成数', '扫码数'];
        $data->push($header1);
        $data->push($header2);
        $faceCount->each(function ($item) use (&$data) {
            $item = json_decode(json_encode($item), true);

            $aa = [];
            foreach ($item as $key => $value) {

                if ($key == 'projectName') {
                    $aa['projectName'] = $value;
                } else {
                    if ($value == 0) {
                        $aa[$key . '-' . 'looknum'] = 0;
                        $aa[$key . '-' . 'playernum'] = 0;
                        $aa[$key . '-' . 'lovenum'] = 0;
                        $aa[$key . '-' . 'outnum'] = 0;
                        $aa[$key . '-' . 'scannum'] = 0;
                    } else {
                        $num = explode(',', $value);
                        $aa[$key . '-' . 'looknum'] = $num['0'];
                        $aa[$key . '-' . 'playernum'] = $num['1'];
                        $aa[$key . '-' . 'lovenum'] = $num['2'];
                        $aa[$key . '-' . 'outnum'] = $num['3'];
                        $aa[$key . '-' . 'scannum'] = $num['4'];
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
                $event->sheet->getDelegate()->setMergeCells(['A1:A2', 'B1:F1', 'G1:K1','L1:P1', 'Q1:U1', 'V1:Z1', 'AA1:AE1']);
            }
        ];
    }
}