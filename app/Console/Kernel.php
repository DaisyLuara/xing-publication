<?php

namespace App\Console;

use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use App\Jobs\ActivePlayerJob;
use App\Jobs\CharacterJob;
use App\Jobs\FaceLogJob;
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
        })->weekly()->fridays()->at('10:25');

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

        //围观渗透率
        $schedule->call(function () {
            FaceLogJob::dispatch()->onQueue('data-clean');
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
