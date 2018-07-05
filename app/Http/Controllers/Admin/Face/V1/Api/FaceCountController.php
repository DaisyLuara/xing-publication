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

        foreach ($ranks as $rank) {
            WeekRankingJob::dispatch($rank)->onQueue('weekRanking');
        }
    }
}
