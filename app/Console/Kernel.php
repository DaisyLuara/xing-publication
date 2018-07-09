<?php

namespace App\Console;

use App\Http\Controllers\Admin\Face\V1\Models\ActivePlayerRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCollectRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceMauMarketRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord;
use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use App\Jobs\WeekRankingJob;
use Carbon\Carbon;
use DB;
use function foo\func;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Admin\Face\V1\Models\FacePeopleTimeRecord;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //点位排名通知
        $schedule->call(function () {
            $data = $this->getRankingData();
            for ($i = 0; $i < count($data); $i++) {
                WeekRankingJob::dispatch($data[$i])->onQueue('weekRanking');
            }
        })->weekly()->fridays()->at('8:00');


        //节目点位活跃玩家清洗
        $schedule->call(function () {
            $date = ActivePlayerRecord::query()->max('date');
            $date = (new Carbon($date))->format('Y-m-d');
            $currentDate = Carbon::now()->toDateString();
            while ($date < $currentDate) {
                $startClientDate = strtotime($date . ' 00:00:00') * 1000;
                $endClientDate = strtotime($date . ' 23:59:59') * 1000;

                $timeArray = [7, 20, 30];

                //按所有人去重 belong='all'
                $sql1 = [];
                for ($i = 0; $i < count($timeArray); $i++) {
                    $sql = DB::connection('ar')->table('face_people_time')
                        ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and fpid>0 and playtime>='$timeArray[$i]000'")
                        ->selectRaw("oid ");
                    if ($date < '2018-07-01') {
                        $sql = $sql->groupBy(DB::raw('fpid*100+oid'));
                    } else {
                        $sql = $sql->groupBy(DB::raw('fpid*10000+oid'));
                    }

                    $sql1[] = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a" . $timeArray[$i]))
                        ->groupBy('oid' . $timeArray[$i])
                        ->selectRaw("oid as oid" . $timeArray[$i] . ",count(*) as playernum" . $timeArray[$i]);
                }
                $query = DB::connection('ar')->table(DB::raw("({$sql1[0]->toSql()}) as b"));
                for ($i = 1; $i < count($timeArray); $i++) {
                    $query = $query->join(DB::raw("({$sql1[$i]->toSql()}) as b" . $i), function ($join) use ($i, $timeArray) {
                        $join->on('b.oid' . $timeArray[0], '=', 'b' . $i . '.oid' . $timeArray[$i]);
                    }, null, null, 'left');
                }
                $query->selectRaw("oid" . $timeArray[0] . " as oid");
                for ($i = 0; $i < count($timeArray); $i++) {
                    $query->selectRaw("playernum" . $timeArray[$i]);
                }
                $allData = $query->get();

                //按节目去重
                $sql2 = [];
                for ($i = 0; $i < count($timeArray); $i++) {

                    $sql = DB::connection('ar')->table('face_people_time')
                        ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and fpid>0 and playtime>='$timeArray[$i]000'")
                        ->selectRaw("oid,belong");
                    if ($date < '2018-07-01') {
                        $sql = $sql->groupBy(DB::raw('fpid*100+oid,belong'));
                    } else {
                        $sql = $sql->groupBy(DB::raw('fpid*10000+oid,belong'));
                    }
                    $sql2[] = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a" . $timeArray[$i]))
                        ->groupBy(DB::raw("oid" . $timeArray[$i] . ",belong" . $timeArray[$i]))
                        ->selectRaw("oid as oid" . $timeArray[$i] . ",belong as belong" . $timeArray[$i] . ",count(*) as playernum" . $timeArray[$i]);
                }
                $query = DB::connection('ar')->table(DB::raw("({$sql2[0]->toSql()}) as b"));
                for ($i = 1; $i < count($timeArray); $i++) {
                    $query = $query->join(DB::raw("({$sql2[$i]->toSql()}) as b" . $i), function ($join) use ($i, $timeArray) {
                        $join->on('b.oid' . $timeArray[0], '=', 'b' . $i . '.oid' . $timeArray[$i])
                            ->on('b.belong' . $timeArray[0], '=', 'b' . $i . '.belong' . $timeArray[$i]);
                    }, null, null, 'left');
                }
                $query = $query->selectRaw("oid" . $timeArray[0] . " as oid,belong" . $timeArray[0] . " as belong");
                for ($i = 0; $i < count($timeArray); $i++) {
                    $query->selectRaw("playernum" . $timeArray[$i]);
                }
                $data = $query->get();


                $count = [];
                foreach ($allData as $item) {
                    $count[] = [
                        'oid' => $item->oid,
                        'belong' => 'all',
                        'playernum7' => $item->playernum7,
                        'playernum20' => $item->playernum20,
                        'playernum30' => $item->playernum30,
                        'date' => $date,
                        'clientdate' => strtotime($date) * 1000
                    ];
                }
                foreach ($data as $item) {
                    $count[] = [
                        'oid' => $item->oid,
                        'belong' => $item->belong,
                        'playernum7' => $item->playernum7,
                        'playernum20' => $item->playernum20,
                        'playernum30' => $item->playernum30,
                        'date' => $date,
                        'clientdate' => strtotime($date) * 1000
                    ];
                }
                DB::connection('ar')->table('xs_face_active_player')
                    ->insert($count);
                $date = (new Carbon($date))->addDay(1)->toDateString();
            }
            ActivePlayerRecord::create(['date' => $currentDate]);
        })->daily()->at('10:15');

        //月活玩家清洗
        $schedule->call(function () {
            $date = FaceMauRecord::query()->max('date');
            $currentDate = Carbon::now()->toDateString();
            while ((new Carbon($date))->format('Y-m') < (new Carbon($currentDate))->format('Y-m')) {
                $startDate = $date;
                $endDate = (new Carbon($date))->endOfMonth()->toDateString();
                $startClientDate = strtotime($startDate . ' 00:00:00') * 1000;
                $endClientDate = strtotime($endDate . ' 23:59:59') * 1000;

                $sql = DB::connection('ar')->table('face_people_time')
                    ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and playtime >= 7000 and oid not in(16, 19, 30, 31, 335, 334, 329, 328, 327)")
                    ->groupBy(DB::raw('fpid * 10000 + oid'))
                    ->selectRaw(" * ");
                $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
                    ->selectRaw("count(*) as playernum")
                    ->first();
                $count = [
                    'active_player' => $data->playernum,
                    'date' => $date
                ];
                DB::connection('ar')->table('xs_face_mau')
                    ->insert($count);
                $date = (new Carbon($date))->addMonth(1)->toDateString();
            }
            FaceMauRecord::create(['date' => $date]);
        })->monthlyOn(1, '8:00');

        //按商场去重月活玩家
        $schedule->call(function () {
            $date = FaceMauMarketRecord::query()->max('date');
            $currentDate = Carbon::now()->toDateString();
            while ((new Carbon($date))->format('Y-m') < (new Carbon($currentDate))->format('Y-m')) {
                $startDate = $date;
                $endDate = (new Carbon($date))->endOfMonth()->toDateString();
                $startClientDate = strtotime($startDate . ' 00:00:00') * 1000;
                $endClientDate = strtotime($endDate . ' 23:59:59') * 1000;

                $sql = DB::connection('ar')->table('face_people_time as fpt')
                    ->join('avr_official as ao', 'fpt.oid', '=', 'ao.oid')
                    ->whereRaw("fpt . clientdate between '$startClientDate' and '$endClientDate' and playtime >= 7000 and fpt . oid not in(16, 19, 30, 31, 335, 334, 329, 328, 327)")
                    ->groupBy(DB::raw('ao.marketid,fpid * 10000 + fpt.oid'))
                    ->selectRaw("marketid,fpid");
                $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
                    ->selectRaw("marketid,count(*) as playernum")
                    ->groupBy('marketid')
                    ->get();
                $count = [];
                foreach ($data as $item) {
                    $count[] = [
                        'active_player' => $item->playernum,
                        'marketid' => $item->marketid,
                        'date' => $date
                    ];
                }
                DB::connection('ar')->table('xs_face_mau_market')
                    ->insert($count);
                $date = (new Carbon($date))->addMonth(1)->toDateString();
            }
            FaceMauMarketRecord::create(['date' => $date]);
        })->monthlyOn(1, '8:00');

        //时间段与人群特征数据清洗
        $schedule->call(function () {
            $date = FaceCollectRecord::query()->max('date');
            $date = (new Carbon($date))->format('Y-m-d');
            $currentDate = Carbon::now()->toDateString();

            while ($date < $currentDate) {
                $startDate = strtotime($date . " 00:00:00") * 1000;
                $endDate = strtotime($date . " 23:59:59") * 1000;

                $century00 = "when age > 8 and age <= 18 then '00'";
                $century90 = "when age > 18 and age <= 28 then '90' ";
                $century80 = "when age > 18 and age <= 38 then '80' ";
                $century70 = "when age > 38 and age <= 48 then '70' ";
                $century = $century00 . $century90 . $century80 . $century70;

                $sql = DB::connection('ar')->table('face_collect')
                    ->selectRaw("date_format(concat(date(date), ' ', hour(date), ':', floor(minute(date) / 30) * 30), '%Y-%m-%d %H:%i') as time,case " . $century . "else 0 end as century,gender,oid,belong")
                    ->whereRaw("clientdate between '$startDate' and '$endDate' and fpid > 0 and type = 'play' ")
                    ->orderBy('isold');

                //按所有人去重 belong='all'
                if ($date < '2018-07-01') {
                    $sql1 = $sql->groupBy(DB::raw('fpid*100+oid'));
                } else {
                    $sql1 = $sql->groupBy(DB::raw('fpid*10000+oid'));
                }
                $allData = DB::connection('ar')->table(DB::raw("({$sql1->toSql()}) as a"))
                    ->groupBy(DB::raw("oid,time,century,gender"))
                    ->orderBy(DB::raw("oid,time,century,gender"))
                    ->selectRaw("oid,time,century,gender,count(*) as looknum")
                    ->get();

                //按节目去重
                if ($date < '2018-07-01') {
                    $sql2 = $sql->groupBy(DB::raw('fpid*100+oid,belong'));
                } else {
                    $sql2 = $sql->groupBy(DB::raw('fpid*10000+oid,belong'));
                }
                $data = DB::connection('ar')->table(DB::raw("({$sql2->toSql()}) as a"))
                    ->groupBy(DB::raw("oid,belong,time,century,gender"))
                    ->orderBy(DB::raw("oid,belong,time,century,gender"))
                    ->selectRaw("oid,belong,time,century,gender,count(*) as looknum")
                    ->get();

                $count = [];
                foreach ($allData as $item) {
                    $item = json_decode(json_encode($item), true);
                    $item['belong'] = 'all';
                    $item['time'] = (new Carbon($item['time']))->addMinutes(30)->format('H:i');
                    $item['date'] = $date;
                    $item['clientdate'] = strtotime($date) * 1000;
                    $count[] = $item;
                }
                foreach ($data as $item) {
                    $item = json_decode(json_encode($item), true);
                    $item['time'] = (new Carbon($item['time']))->addMinutes(30)->format('H:i');
                    $item['date'] = $date;
                    $item['clientdate'] = strtotime($date) * 1000;
                    $count[] = $item;
                }
                $count = array_chunk($count, 8000);
                for ($i = 0; $i < count($count); $i++) {
                    DB::connection('ar')->table('xs_face_collect_character')->insert($count[$i]);
                }
                $date = (new Carbon($date))->addDay(1)->toDateString();
            }
            FaceCollectRecord::create(['date' => $currentDate]);
        })->daily()->at('8:00');
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected function getRankingData()
    {

//        $startDate = Carbon::now()->addDay(-7)->toDateString();
//        $endDate = Carbon::now()->addDay(-1)->toDateString();
        $startDate = '2018-04-01';
        $endDate = '2018-04-07';
        $marketPoint = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->whereRaw(" date_format(fcl . date, '%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('sid', '=', 1)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->selectRaw("fcl . oid")
            ->get();
        $limitMarket = (floor($marketPoint->count() / 100) + 1) * 10;
        $faceCountMarket = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format(fcl . date, '%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('ao.sid', '=', 1)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitMarket)
            ->selectRaw("  ao . bd_uid as uid,as.realname as userName,aos . sid as sceneId,aos . name as sceneName,fcl . oid as oid,aoa . name as areaName,aom . name as marketName,ao . name as pointName,sum(looknum) as looknum")
            ->get();

        $cinemaPoint = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->whereRaw(" date_format(fcl . date, '%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('sid', '=', 8)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->selectRaw("fcl . oid")
            ->get();
        $limitCinema = (floor($cinemaPoint->count() / 100) + 1) * 10;
        $faceCountCinema = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format(fcl . date, '%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('ao.sid', '=', 8)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitCinema)
            ->selectRaw("  ao . bd_uid as uid,as.realname as userName,aos . sid as sceneId,aos . name as sceneName,fcl . oid as oid,aoa . name as areaName,aom . name as marketName,ao . name as pointName,sum(looknum) as looknum")
            ->get();

        $otherPoint = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->whereRaw(" date_format(fcl . date, '%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('ao.sid', [0, 1, 3, 8, 14, 15])
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->selectRaw("fcl . oid")
            ->get();
        $limitOther = (floor($otherPoint->count() / 100) + 1) * 10;
        $faceCount_other = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format(fcl . date, '%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('ao.sid', [0, 1, 3, 8, 14, 15])
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitOther)
            ->selectRaw("  ao . bd_uid as uid,as.realname as userName,aos . sid as sceneId,aos . name as sceneName,fcl . oid as oid,aoa . name as areaName,aom . name as marketName,ao . name as pointName,sum(looknum) as looknum")
            ->get();

        $data = [];
        $i = 1;
        $faceCountMarket->each(function ($item) use (&$data, $startDate, $endDate, &$i) {
            $data[] = [
                'ar_user_id' => $item->uid,
                'ar_user_name' => $item->userName,
                'point_id' => $item->oid,
                'point_name' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'scene_id' => $item->sceneId,
                'scene_name' => $item->sceneName,
                'looknum_average' => round($item->looknum / 7, 0),
                'ranking' => $i,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'date' => Carbon::now()->toDateString(),
            ];
            $i++;
        });

        $i = 1;
        $faceCountCinema->each(function ($item) use (&$data, $startDate, $endDate, &$i) {
            $data[] = [
                'ar_user_id' => $item->uid,
                'ar_user_name' => $item->userName,
                'point_id' => $item->oid,
                'point_name' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'scene_id' => $item->sceneId,
                'scene_name' => $item->sceneName,
                'looknum_average' => round($item->looknum / 7, 0),
                'ranking' => $i,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'date' => Carbon::now()->toDateString(),
            ];
            $i++;
        });

        $i = 1;
        $faceCount_other->each(function ($item) use (&$data, $startDate, $endDate, &$i) {
            $data[] = [
                'ar_user_id' => $item->uid,
                'ar_user_name' => $item->userName,
                'point_id' => $item->oid,
                'point_name' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'scene_id' => $item->sceneId,
                'scene_name' => "其他场景",
                'looknum_average' => round($item->looknum / 7, 0),
                'ranking' => $i,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'date' => Carbon::now()->toDateString(),
            ];
            $i++;
        });
        WeekRanking::query()->insert($data);
        return $data;
    }
}
