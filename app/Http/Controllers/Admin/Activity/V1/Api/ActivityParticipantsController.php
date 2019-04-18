<?php

namespace App\Http\Controllers\Admin\Activity\V1\Api;

use App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser;
use App\Http\Controllers\Admin\Activity\V1\Models\RankAmountConfig;
use App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill;
use App\Http\Controllers\Admin\Activity\V1\Transformer\ActivityParticipantsTransformer;
use App\Http\Controllers\Admin\Activity\V1\Models\ActivityParticipant;
use App\Http\Controllers\Controller;
use App\Jobs\RedpackJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Activity\V1\Request\ActivityParticipantsRequest;
use Illuminate\Support\Collection;

class ActivityParticipantsController extends Controller
{

    public function index(Request $request, ActivityParticipant $activity_participant)
    {

        $query = $activity_participant->query();

        //状态
        if ($request->has('pass')) {
            $query->where('aid', $request->get('aid'));
        }

        //玩法配置
        if ($request->has('aid')) {
            $query->where('aid', $request->get('aid'));

            //年会排行榜
            if ($request->aid === 32) {
                $query->where('pass', 1);
                $query->orderByDesc('value')->orderBy('clientdate');

                /** @var ActivityParticipant|Collection $activity_participants */
                $activity_participants = $query->take(5)->get();
                return $this->response->collection($activity_participants, new ActivityParticipantsTransformer());
            }
        }

        $activity_participants = $query->paginate(10);
        return $this->response->paginator($activity_participants, new ActivityParticipantsTransformer());
    }

    public function redPack(ActivityParticipantsRequest $request)
    {
        /** @var ActivityParticipant $activity_participant */
        $activity_participant = ActivityParticipant::query()
            ->with('arWxUser')
            ->where('auid', $request->auid)
            ->where('aid', 32)//年会活动
            ->firstOrFail();

        /** @var ArWxUser $ar_wx_user */
        $ar_wx_user = ArWxUser::query()->where('uid', $activity_participant->uid)
            ->where('wiid', 88)->firstOrFail();

        //当前用户 如果有交易流水 无论失败与否 ，不再发送红包
        $redpackBill = RedPackBill::query()->where('re_openid', $ar_wx_user->openid)->where('scene_id', 'PRODUCT_4')->first();

        abort_if($redpackBill, 500, '已经发送过了！');

        $rank = $request->get('rank');

        /** @var RankAmountConfig $rank_amount_config */
        $rank_amount_config = RankAmountConfig::query()->where('rank', $rank + 1)->firstOrFail(['amount']);
        $totalAmount = $rank_amount_config->amount;

        //检查当前奖项是否被发送了 - 注意 元转换成分
        $hasSend = RedPackBill::query()->where('total_amount', $totalAmount * 100)->where('scene_id', 'PRODUCT_4')->first();
        abort_if($hasSend, 500, '当前奖项已经被发送了');

        $redPackData = [
            'send_name' => "排行榜第" . ($rank + 1) . "名",
            're_openid' => $ar_wx_user->openid,
            'total_amount' => $totalAmount,
            'wishing' => '新年快乐',
            'act_name' => '新年排行榜',
            'remark' => '发送给用户 ' . $activity_participant->username,
            'scene_id' => 'PRODUCT_4',
        ];
        RedpackJob::dispatch($redPackData)->onQueue('redpack');

    }

}
