<?php

namespace App\Http\Controllers\Admin\Activity\V1\Api;

use App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig;
use App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill;
use App\Http\Controllers\Admin\Activity\V1\Transformer\ActivityParticipantsTransformer;
use App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant;
use App\Http\Controllers\Controller;
use App\Jobs\RedpackJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Activity\V1\Request\ActivityParticipantsRequest;

class ActivityParticipantsController extends Controller
{

    public function index(Request $request, ActivityParticipant $activityParticipant)
    {

        $query = $activityParticipant->query();

        //状态
        if ($request->has('pass')) {
            $query->where('aid', $request->aid);
        }

        //玩法配置
        if ($request->has('aid')) {
            $query->where('aid', $request->aid);

            //年会排行榜
            if ($request->aid == 32) {
                $query->where('pass', 1);
                $query->orderByDesc('value')->orderBy('clientdate', 'asc');

                $activityParticipants = $query->take(5)->get();
                return $this->response->collection($activityParticipants, new ActivityParticipantsTransformer());
            }
        }

        $activityParticipants = $query->paginate(10);
        return $this->response->paginator($activityParticipants, new ActivityParticipantsTransformer());
    }

    public function redPack(ActivityParticipantsRequest $request)
    {
        $activityParticipant = ActivityParticipant::query()
            ->with('arWxUser')
            ->where('auid', $request->auid)
//            ->where('aid', 32)//年会活动
            ->firstOrFail();
        $arWxUser = $activityParticipant->arWxUser;

        //当前用户 如果有交易流水 无论失败与否 ，不再发送红包
        $redpackBill = RedPackBill::query()->where('re_openid', $arWxUser->openid)->where('scene_id', 'PRODUCT_4')->first();

        abort_if($redpackBill, 500, '已经发送过了！');

        $rank = $request->rank;
        $rankAmountConfig = RankAmountConfig::query()->where('rank', $rank + 1)->firstOrFail(['amount']);
        $totalAmount = $rankAmountConfig->amount;

        //检查当前奖项是否被发送了 - 注意 元转换成分
        $hasSend = RedPackBill::query()->where('total_amount', $totalAmount * 100)->where('scene_id', 'PRODUCT_4')->first();
        abort_if($hasSend, 500, '当前奖项已经被发送了');

        $redPackData = [
            'send_name' => "排行榜第" . ($rank + 1) . "名",
            're_openid' => $arWxUser->openid,
            'total_amount' => $totalAmount,
            'wishing' => '新年快乐',
            'act_name' => '新年排行榜',
            'remark' => '发送给用户 ' . $activityParticipant->username,
            'scene_id' => 'PRODUCT_4',
        ];
        RedpackJob::dispatch($redPackData)->onQueue('redpack');

    }

}
