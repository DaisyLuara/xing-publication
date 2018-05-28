<?php

namespace App\Console;

use App\Console\Commands\GizWitsRestart;
use Carbon\Carbon;
use DB;
use EasyWeChat;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $data = $this->getTenUser();
        /** @var \EasyWeChat\OpenPlatform\Application $openPlatform */
        $openPlatform = EasyWeChat::openPlatform();
        $officialAccount = $openPlatform->officialAccount('', '');
        $schedule->call(function () use ($officialAccount, $data) {
            for ($i = 0; $i < $data->count(); $i++) {
                $text = new Text('你负责的点位"' . $data[$i]['pointName'] . '"上周日均围观数处于倒数10名，日均围观数：' . $data[$i]['looknum']);
                $openId = '';
                $officialAccount->customer_service->message($text)->to($openId)->send();
            }

        })->cron('0 0 10 ? * FRI');

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
        $startDate = Carbon::now()->addDay(-12)->toDateString();
        $endDate = Carbon::now()->addDay(-5)->toDateString();
        $faceCount = DB::connection('ar')->table('face_count_log as fcl')
            ->join('admin_per_oid as apo', 'fcl.oid', '=', 'apo.oid')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit(10)
            ->selectRaw("  apo.uid as uid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();
        $data = [];
        $faceCount->each(function ($item) use (&$data) {
            $data[] = [
                'uid' => $item->uid,
                'pointName' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'looknum' => round($item->looknum / 7, 0)
            ];
        });
        return $data;
    }
}
