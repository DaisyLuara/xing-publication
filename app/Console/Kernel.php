<?php

namespace App\Console;

use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use App\Jobs\ActivePlayerJob;
use App\Jobs\CharacterJob;
use App\Jobs\CharacterTimesJob;
use App\Jobs\FaceLogJob;
use App\Jobs\FaceLogTimesJob;
use App\Jobs\FacePlaytimesCharacterJob;
use App\Jobs\FacePlaytimesLogJob;
use App\Jobs\MauJob;
use App\Jobs\TeamBonusJob;
use App\Jobs\WeekRankingJob;
use App\Jobs\ContractReceiveDatesJob;
use Carbon\Carbon;
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
        if (env('APP_ENV') !== 'local') {

            //点位排名通知
            $schedule->call(function () {
                $data = $this->getRankingData();
                for ($i = 0; $i < count($data); $i++) {
                    WeekRankingJob::dispatch($data[$i])->onQueue('weekRanking');
                }
            })->weekly()->fridays()->at('10:25');

            //count数据清洗
            $schedule->call(function () {
                ActivePlayerJob::dispatch()->onQueue('data-clean');
            })->daily()->at('7:00');

            //围观渗透率
            $schedule->call(function () {
                FaceLogJob::dispatch()->onQueue('data-clean');
            })->daily()->at('7:10');

            //时间段与人群特征数据清洗
            $schedule->call(function () {
                CharacterJob::dispatch()->onQueue('data-clean');
            })->daily()->at('7:20');

            //围观人次渗透率
            $schedule->call(function () {
                FaceLogTimesJob::dispatch()->onQueue('data-clean');
            })->daily()->at('7:30');

            //人次时间段与人群特征数据清洗
            $schedule->call(function () {
                CharacterTimesJob::dispatch()->onQueue('data-clean');
            })->daily()->at('7:40');

            //7s,15s,21s人群特征数据清洗
            $schedule->call(function () {
                FacePlaytimesCharacterJob::dispatch()->onQueue('data-clean');
            })->daily()->at('6:00');

            //7s,15s,21s渗透率
            $schedule->call(function () {
                FacePlaytimesLogJob::dispatch()->onQueue('data-clean');
            })->daily()->at('6:10');

            //月活玩家清洗(按人和商场去重)
            $schedule->call(function () {
                MauJob::dispatch()->onQueue('data-clean');
            })->monthlyOn(1, '6:00');

            //绩效清洗
            $schedule->call(function () {
                TeamBonusJob::dispatch()->onQueue('data-clean');
            })->daily()->at('8:00');

            //合同收款日期通知
            $schedule->call(function () {
                ContractReceiveDatesJob::dispatch()->onQueue('data-clean');
            })->daily()->at('8:00');
        }
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
        $startDate = Carbon::now()->addDay(-7)->toDateString();
        $endDate = Carbon::now()->addDay(-1)->toDateString();

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

        return $ranks;
    }
}
