<?php

namespace App\Observers;

use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use Carbon\Carbon;
use Log;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ThirdPartyUserObserver
{
    public function created(ThirdPartyUser $thirdPartyUser)
    {
        //开卡时间匹配
        $date = Carbon::now()->toDateString();
        $applyDate = Carbon::parse($thirdPartyUser->mall_card_apply_time)->toDateString();
        Log::info('card_apply_time', ['date' => $date, 'applyDate' => $applyDate]);

        if ($applyDate == $date) {
            //赠送猫酷积分
            $mall_coo = app('mall_coo');
            $sUrl = 'https://openapi10.mallcoo.cn/User/Score/v1/Plus/ByOpenUserID/';

            $data = [
                "OpenUserId" => $thirdPartyUser->mallcoo_open_user_id,
                "Score" => 500,
                "TransID" => uniqid() . $thirdPartyUser->mallcoo_open_user_id,
                "ScoreEvent" => 10,
            ];

            $result = $mall_coo->send($sUrl, $data);

            //积分记录
            if ($result['Code'] == 1) {
                $thirdPartyUser->mallcoo_score_histories()->create([
                    'score' => 500,
                    'description' => '新会员赠送500积分',
                    'type' => 'increase',
                ]);
            }
        }

    }
}