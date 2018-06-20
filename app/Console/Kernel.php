<?php

namespace App\Console;

use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
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
        $schedule->call(function () {
            $data = $this->getTenUser();
            for ($i = 0; $i < 2; $i++) {
                //yq,cz
                $openId = ['oNN6q0sZDI_OSTV6rl0rPeHjPgH8', 'oNN6q0pq-f0-Z2E2gb0QeOmY4r-M'];
                WeekRankingJob::dispatch($data[$i], $openId[$i])->onQueue('weekRanking');
            }
        })->weekly()->fridays()->at('10:00');
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

    protected function getTenUser()
    {

        //$startDate = Carbon::now()->addDay(-12)->toDateString();
        //$endDate = Carbon::now()->addDay(-5)->toDateString();
        $startDate = '2018-04-01';
        $endDate = '2018-04-07';

        $totalPoint = DB::connection('ar')->table('face_count_log')
            ->whereRaw(" date_format(date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('oid')
            ->selectRaw("oid")
            ->get();
        $limitNum = floor($totalPoint->count() / 10);

        $faceCount = DB::connection('ar')->table('face_count_log as fcl')
            ->join('admin_per_oid as apo', 'fcl.oid', '=', 'apo.oid')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '=', 'all')
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitNum)
            ->selectRaw("  apo.uid as uid,fcl.oid as oid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();

        $data = [];
        $faceCount->each(function ($item) use (&$data, $startDate, $endDate) {
            WeekRanking::create([
                'ar_user_id' => $item->uid,
                'point_id' => $item->oid,
                'looknum_average' => round($item->looknum / 7, 0),
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
            $data[] = [
                'uid' => $item->uid,
                'pointName' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'looknum' => round($item->looknum / 7, 0)
            ];
        });
        return $data;
    }
}
