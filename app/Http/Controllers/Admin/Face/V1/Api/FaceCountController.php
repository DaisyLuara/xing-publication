<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\WeekRankingJob;

class FaceCountController extends Controller
{
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
