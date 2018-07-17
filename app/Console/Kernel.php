<?php

namespace App\Console;

use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use App\Jobs\ActivePlayerJob;
use App\Jobs\CharacterJob;
use App\Jobs\MauJob;
use App\Jobs\WeekRankingJob;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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

        //活跃玩家清洗
        $schedule->call(function () {
            ActivePlayerJob::dispatch()->onQueue('data-clean');
        })->daily()->at('7:00');

        //月活玩家清洗(按人和商场去重)
        $schedule->call(function () {
            MauJob::dispatch()->onQueue('data-clean');
        })->monthlyOn(1, '7:00');

        //时间段与人群特征数据清洗
        $schedule->call(function () {
            CharacterJob::dispatch()->onQueue('data-clean');
        })->daily()->at('7:00');

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
