<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GizWitsRestart;
use EasyWeChat;
use EasyWeChat\Kernel\Messages\Text;

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
        $schedule->call(function (){
        $officialAccount = EasyWeChat::officialAccount();
        $text = new Text('test');
        return $officialAccount->customer_service->message($text)->to('ott1x1V0a7b_KKH2XnLs_-YISIso')->send();

        })->cron('0 40 16  * * ?');
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
}
