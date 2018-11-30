<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\WeekRankingJob;
use DB;

class FaceCountController extends Controller
{
    public function aaa()
    {
        $date = '2018-07-01';
        $faceCount1 = DB::connection('ar')->table('xs_face_count_log as fcl')
            ->join('ar_product_list as apl', 'belong', '=', 'versionname')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw("date_format(fcl.date, '%Y-%m-%d')='$date' and apl.online<>0 and fcl.oid not in ('16', '19', '30', '31', '177','182','327','328','329','334','335','540') and aom.marketid <> '15'")
            ->groupBy(DB::raw("date_format(fcl.date, '%Y-%m-%d'),fcl.oid,fcl.belong"))
            ->orderBy('date')
            ->orderBy('apl.id')
            ->orderBy('looknum', 'desc')
            ->selectRaw("date_format(fcl.date, '%Y-%m-%d') as date,apl.name as name,apl.online as online,fcl.belong as belong,sum(playernum7)as playernum7,sum(playernum15) as playernum15 ,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum");

        $faceCount2 = DB::connection('ar')->table(DB::raw("({$faceCount1->toSql()}) a,(select @gn := 0)  b"))
            ->selectRaw("  @gn := case when (@date=date and @name = name) then @gn + 1 else 1 end gn,@date:=date date,@name := name name,online,belong,playernum7,playernum15,playernum21,omo_outnum");

        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount2->toSql()}) c"))
            ->selectRaw("name,online,belong,sum(playernum7) as playernum7,sum(playernum15) as playernum15,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum")
            ->whereRaw("gn<=100")
            ->groupBy('name')
            ->get();

        $count = [];
        foreach ($faceCount as $item) {
            $player7Money = round($item->playernum7 * 0.01, 0);
            $player15Money = round($item->playernum15 * 0.02, 0);
            $player21Money = round($item->playernum21 * 0.05, 0);
            $uCPAMoney = round($item->omo_outnum * 0.2, 0);
            $totalMoney = $player7Money + $player15Money + $player21Money + $uCPAMoney;

            $launchDate = date('Y-m-d', $item->online);

            $teamProject = TeamProject::query()->where('belong', $item->belong)->first();
            //投放时长 当前日期-投放日期
            $launchTime = (new Carbon($date))->diffInDays($launchDate);
            $factor = 0;
            if ($teamProject) {
                if ($launchTime <= 30) {
                    //主管确认
                    if ($teamProject->status == 4) {
                        //提前制作时间 投放时间-上线时间
                        $advanceTime = (new Carbon($launchDate))->diffInDays($teamProject->online_date);
                        if ($advanceTime >= 90) {
                            $factor = 1.2;
                        }
                        if ($advanceTime >= 60 && $advanceTime < 90) {
                            $factor = 1.1;
                        }
                        if ($advanceTime < 60) {
                            if ($teamProject->project_attribute <= 2) {
                                $factor = 0.8;
                            } else {
                                $factor = 1;
                            }
                        }
                    }
                    //运营确认
                    if ($teamProject->status == 3) {
                        if ($teamProject->project_attribute <= 2) {
                            $factor = 0.8;
                        } else {
                            $factor = 1;
                        }
                    }
                }
                if ($launchTime > 30 && $launchTime <= 60) {
                    $factor = 0.6;
                }
                if ($launchTime > 60 && $launchTime <= 90) {
                    $factor = 0.4;
                }
                if ($launchTime > 90 && $launchTime <= 120) {
                    $factor = 0.2;
                }
            }
            $count[] = [
                'project_name' => $item->name,
                'belong' => $item->belong,
                'money' => $totalMoney,
                'factor' => $factor,
                'date' => $date
            ];
        }
        DB::table('team_bonuses')->insert($count);

        $data = DB::table('team_projects as tp')
            ->join('team_project_members as tpm', 'tp.id', '=', 'tpm.team_project_id')
            ->join('team_bonuses as tb', 'tp.belong', '=', 'tb.belong')
            ->selectRaw("user_id,tp.project_name as project_name,tp.belong as belong,money,factor,rate")
            ->get();

        $rewards = [];
        foreach ($data as $item) {
            $rewards[]= [
                'user_id' => $item->user_id,
                'project_name' => $item->project_name,
                'belong' => $item->belong,
                'money' => round($item->money * $item->factor * $item->rate, 2),
                'date' => $date
            ];
        }
        DB::table('team_person_rewards')->insert($rewards);

    }

    public function weekRanking(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $points = getPointsByScene($startDate, $endDate);
        $selectScenes = [
            [
                'sid' => 16,
                'limit' => (floor($points->where('sid', 16)->sum('total') / 50) + 1) * 5,
                'name' => 'electrical_market',
                'avg' => 100
            ],
            [
                'sid' => 11,
                'limit' => (floor($points->where('sid', 11)->sum('total') / 50) + 1) * 5,
                'name' => 'gym',
                'avg' => 35
            ],
            [
                'sid' => 8,
                'limit' => (floor($points->where('sid', 8)->sum('total') / 50) + 1) * 5,
                'name' => 'cinema',
                'avg' => 50

            ],
            [
                'sid' => 5,
                'limit' => (floor($points->where('sid', 5)->sum('total') / 50) + 1) * 5,
                'name' => 'merchant',
                'avg' => 100

            ],
            [
                'sid' => 1,
                'limit' => (floor($points->where('sid', 1)->sum('total') / 50) + 1) * 5,
                'name' => 'market',
                'avg' => 100
            ],
        ];

        $ranks = [];
        foreach ($selectScenes as $selectScene) {
            $ranks = array_merge(getFaceCountByScene($startDate, $endDate, $selectScene), $ranks);
        }

        WeekRanking::query()->insert($ranks);

        foreach ($ranks as $rank) {
            WeekRankingJob::dispatch($rank);
        }
    }
}
