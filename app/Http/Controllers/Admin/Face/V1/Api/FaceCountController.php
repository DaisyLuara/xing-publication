<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use EasyWeChat;
use DB;
use Carbon\Carbon;

class FaceCountController extends Controller
{
    public function weekRanking(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $points = getPointsByScene($startDate, $endDate);
        $selectScenes = [
            [
                'sid' => [1, 8],
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

        foreach ($ranks as $rank) {
            $officialAccount = EasyWeChat::officialAccount();
            $message = [
                'touser' => "oNN6q0sZDI_OSTV6rl0rPeHjPgH8",
                'template_id' => 'siyJMjigeMMNpXrFSsvz6rvrKQh9Gf5RcfbiVYFQFyY',
                'data' => [
                    'first' => '你好，你的上周点位排名情况如下',
                    'keyword1' => $rank['point_name'],
                    'keyword2' => "日均围观数：" . $rank['looknum_average'] . "\r\n" . "点位排名：倒数第" . $rank['ranking'] . "\r\n" . "场景分类：" . $rank['scene_name'] . "\r\n" . "时间区间：" . $rank['start_date'] . "至" . $rank['end_date'],
                    'remark' => '再接再厉',
                ]
            ];
            $officialAccount->template_message->send($message);
        }
    }
}
