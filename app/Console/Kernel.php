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
            $ranks = $this->getRankingData();
            foreach ($ranks as $rank) {
                WeekRankingJob::dispatch($rank);
            }
        })->weekly()->fridays()->at('14:20');
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
                'sid' => [0, 1, 3, 8, 14, 15],
                'limit' => (floor($points->whereNotIn('sid', [1, 8])->sum() / 100) + 1) * 10,
                'name' => 'other'
            ],
            [
                'sid' => 8,
                'limit' => (floor($points->where('sid', 8)->sum() / 100) + 1) * 10,
                'name' => 'cinema'
            ],
            [
                'sid' => 1,
                'limit' => (floor($points->where('sid', 1)->sum() / 100) + 1) * 10,
                'name' => 'market'
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
