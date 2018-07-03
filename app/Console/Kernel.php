<?php

namespace App\Console;

use App\Http\Controllers\Admin\Face\V1\Models\FaceCollectRecord;
use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use App\Jobs\WeekRankingJob;
use Carbon\Carbon;
use DB;
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
        })->weekly()->fridays()->at('14:20');

        //玩家互动时间清洗
        $schedule->call(function () {
            $max_id = FacePeopleTimeRecord::query()->max('max_id');
            $data = DB::connection('ar')->table('face_people_time')
                ->where('id', '>', $max_id)
                ->groupBy('oid')
                ->groupBy('belong')
                ->groupBy(DB::raw("date_format(date,'%Y-%m-%d')"))
                ->orderBy('date')
                ->orderBy('oid')
                ->selectRaw("oid,belong,count(*) as playernum,sum(playtime) as playtime,date_format(date,'%Y-%m-%d') as date")
                ->get();
            $count = [];
            foreach ($data as $item) {
                $item = json_decode(json_encode($item), true);
                $count[] = $item;
            }
            DB::connection('ar')->table('face_people_time_count')
                ->insert($count);

            $max_id = DB::connection('ar')->table('face_people_time')
                ->max('id');

            FacePeopleTimeRecord::create(['max_id' => $max_id]);
        })->daily()->at('8:00');

        //月活玩家清洗
        $schedule->call(function () {
            $startDate = Carbon::now()->addMonth(-1)->toDateString();
            $endDate = Carbon::now()->addDay(-1)->toDateString();
            $startClientDate = strtotime($startDate . ' 00:00:00') * 1000;
            $endClientDate = strtotime($endDate . ' 23:59:59') * 1000;

            $sql = DB::connection('ar')->table('face_people_time')
                ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and playtime > 7000 and oid not in (16, 19, 30, 31, 335, 334, 329, 328, 327)")
                ->groupBy(DB::raw('fpid * 10000 + oid'))
                ->selectRaw("*");
            $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
                ->selectRaw("count(*) as playernum")
                ->first();
            $date = Carbon::now()->addMonth(-1)->format('Y-m-d');
            $count = [
                'playernum' => $data->playernum,
                'date' => $date
            ];
            DB::connection('ar')->table('face_people_time_mau')
                ->insert($count);
        })->monthlyOn(1, '8:00');

        //按点位月活玩家
        $schedule->call(function () {
            $startDate = Carbon::now()->addMonth(-1)->toDateString();
            $endDate = Carbon::now()->addDay(-1)->toDateString();
            $startClientDate = strtotime($startDate . ' 00:00:00') * 1000;
            $endClientDate = strtotime($endDate . ' 23:59:59') * 1000;

            $sql = DB::connection('ar')->table('face_people_time')
                ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and playtime > 7000 and oid not in (16, 19, 30, 31, 335, 334, 329, 328, 327)")
                ->groupBy(DB::raw('oid,fpid * 10000 + oid'))
                ->selectRaw("*");
            $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
                ->selectRaw("oid,count(*) as playernum")
                ->groupBy('oid')
                ->get();
            $date = Carbon::now()->addMonth(-1)->format('Y-m-d');

            $count = [];
            foreach ($data as $item) {
                $count[] = [
                    'playernum' => $item->playernum,
                    'oid' => $item->oid,
                    'date' => $date
                ];
            }
            DB::connection('ar')->table('face_people_time_mau_point')
                ->insert($count);
        })->monthlyOn(1, '8:00');

        //时间段与人群特征数据清洗
        $schedule->call(function () {
            $date = FaceCollectRecord::query()->max('date');
            $date = (new Carbon($date))->format('Y-m-d');
            $currentDate = Carbon::now()->toDateString();

            while ($date < $currentDate) {
                $startDate = strtotime($date . " 00:00:00") * 1000;
                $endDate = strtotime($date . " 23:59:59") * 1000;

                $time1 = "when date_format(date, '%H:%i:%s') < '10:00:00' then '10:00' ";
                $time2 = "when date_format(date, '%H:%i:%s') between '10:00:00' and '11:59:59' then '12:00' ";
                $time3 = "when date_format(date, '%H:%i:%s') between '12:00:00' and '13:59:59' then '14:00' ";
                $time4 = "when date_format(date, '%H:%i:%s') between '14:00:00' and '15:59:59' then '16:00' ";
                $time5 = "when date_format(date, '%H:%i:%s') between '16:00:00' and '17:59:59' then '18:00' ";
                $time6 = "when date_format(date, '%H:%i:%s') between '18:00:00' and '19:59:59' then '20:00' ";
                $time7 = "when date_format(date, '%H:%i:%s') between '20:00:00' and '21:59:59' then '22:00' ";
                $time8 = "when date_format(date, '%H:%i:%s') > '22:00:00' then '24:00' ";
                $time = $time1 . $time2 . $time3 . $time4 . $time5 . $time6 . $time7 . $time8;

                $century00 = "when age>8 and age<=18 then '00'";
                $century90 = "when age>18 and age<=28 then '90' ";
                $century80 = "when age>18 and age<=38 then '80' ";
                $century70 = "when age>38 and age<=48 then '70' ";
                $century = $century00 . $century90 . $century80 . $century70;

                $sql = DB::connection('ar')->table('face_collect')
                    ->selectRaw("case " . $time . "else 0 end as time,case " . $century . "else 0 end as century,gender,oid,belong")
                    ->whereRaw("clientdate between '$startDate' and '$endDate'")
                    ->groupBy(DB::raw('oid,belong,fpid'));
                $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
                    ->groupBy(DB::raw("oid,belong,time,century,gender"))
                    ->orderBy(DB::raw("oid,belong,time,century,gender"))
                    ->selectRaw("oid,belong,time,century,gender,count(*) as looknum")
                    ->get();

                $count = [];
                foreach ($data as $item) {
                    $item = json_decode(json_encode($item), true);
                    $item['date'] = $date;
                    $count[] = $item;
                }
                DB::connection('ar')->table('face_collect_character')
                    ->insert($count);
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
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('sid', '=', 1)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->selectRaw("fcl.oid")
            ->get();
        $limitMarket = (floor($marketPoint->count() / 100) + 1) * 10;
        $faceCountMarket = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('ao.sid', '=', 1)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitMarket)
            ->selectRaw("  ao.bd_uid as uid,as.realname as userName,aos.sid as sceneId,aos.name as sceneName,fcl.oid as oid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();

        $cinemaPoint = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('sid', '=', 8)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->selectRaw("fcl.oid")
            ->get();
        $limitCinema = (floor($cinemaPoint->count() / 100) + 1) * 10;
        $faceCountCinema = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('ao.sid', '=', 8)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitCinema)
            ->selectRaw("  ao.bd_uid as uid,as.realname as userName,aos.sid as sceneId,aos.name as sceneName,fcl.oid as oid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();

        $otherPoint = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('ao.sid', [0, 1, 3, 8, 14, 15])
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->selectRaw("fcl.oid")
            ->get();
        $limitOther = (floor($otherPoint->count() / 100) + 1) * 10;
        $faceCount_other = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('ao.sid', [0, 1, 3, 8, 14, 15])
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitOther)
            ->selectRaw("  ao.bd_uid as uid,as.realname as userName,aos.sid as sceneId,aos.name as sceneName,fcl.oid as oid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
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
