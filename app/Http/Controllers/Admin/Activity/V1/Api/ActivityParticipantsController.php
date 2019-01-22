<?php

namespace App\Http\Controllers\Admin\Activity\V1\Api;

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
            ->where('aid', 32)//年会活动
            ->firstOrFail();
        $arWxUser = $activityParticipant->arWxUser;

        //如果有交易流水 无论失败与否 ，不再发送红包
        $redpackBill = RedPackBill::query()->where('re_openid', $arWxUser->openid)->first();

        abort_if($redpackBill, 500, '已经发送过了！');

        RedpackJob::dispatch($request->rank, $arWxUser->openid)->onQueue('redpack');

    }


}
