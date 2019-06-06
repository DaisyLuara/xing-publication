<?php

namespace App\Http\Controllers\Admin\Ad\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime;
use App\Http\Controllers\Admin\Ad\V1\Request\AdPlanTimeRequest;
use App\Http\Controllers\Admin\Ad\V1\Transformer\AdPlanTimeTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class AdPlanTimeController extends Controller
{

    public function index(AdPlanTime $adPlanTime,Request $request){

        $query = $adPlanTime->query();
        if ($request->get('atiid')) {
            $query->where('atiid', '=', $request->get('atiid'));
        }
        $advertisements = $query->orderBy('id', 'desc')
            ->paginate(10);

        return $this->response->paginator($advertisements, new AdPlanTimeTransformer());
    }

    public function show(AdPlanTime $adPlanTime)
    {
        return $this->response->item($adPlanTime, new AdPlanTimeTransformer());
    }

    public function store(AdPlanTimeRequest $request, AdPlanTime $adPlanTime, AdPlan $adPlan): Response
    {

//        $ad = Advertisement::query()->findOrFail($request->get('aid'));
        //广告行业
//        if ($ad->atid !== $adPlan->atid) {
//            abort(4122, '广告素材与广告方案的行业不同');
//        }

        $existPlanTime = AdPlanTime::query()->where('aid', '=', $request->get('aid'))
            ->where('atiid', '=', $request->get('atiid'))
            ->first();

        if ($existPlanTime) {
            abort(422, '该方案中已存在该素材，请前往修改');
        }

        $updateParams = [
            'aid' => $request->get('aid'),
            'atiid' => $request->get('atiid'),
            'cdshow' => $request->get('cdshow'),
            'ktime' => $request->get('ktime'),
            'only' => $request->get('only'),
            'visiable' => $request->get('visiable'),
            'shm' => $request->get('shm') ? (int)Carbon::parse($request->get('shm'), 'UTC')->format('Hi') : 0,
            'ehm' => $request->get('ehm') ? (int)Carbon::parse($request->get('ehm'), 'UTC')->format('Hi') : 0,
        ];

        if ($adPlan->type === AdPlan::TYPE_BID_SCREEN) {
            $updateParams = array_merge($updateParams, [
                'mode' => $request->get('mode') ?? 'fullscreen',
                'ori' => $request->get('ori') ?? 'center',
                'screen' => $request->get('screen') ?? 0,
            ]);
        }

        /** @var AdPlanTime $adPlanTime */
        $adPlanTime->fill($updateParams)->save();

        activity('create_ad_plan_time')
            ->causedBy($this->user())
            ->performedOn($adPlanTime)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $updateParams])
            ->log('新增广告方案排期');

        return $this->response->noContent();
    }

    /**
     * 一般编辑
     * @param AdPlanTimeRequest $request
     * @param AdPlanTime $adPlanTime
     * @return Response
     */
    public function update(AdPlanTimeRequest $request, AdPlanTime $adPlanTime): Response
    {
        $updateParams = [
            'cdshow' => $request->get('cdshow'),
            'ktime' => $request->get('ktime'),
            'only' => $request->get('only'),
            'visiable' => $request->get('visiable'),
            'shm' => $request->get('shm') ? (int)Carbon::parse($request->get('shm'), 'UTC')->format('Hi') : 0,
            'ehm' => $request->get('ehm') ? (int)Carbon::parse($request->get('ehm'), 'UTC')->format('Hi') : 0,
        ];

        if ($adPlanTime->ad_plan->type === AdPlan::TYPE_BID_SCREEN) {
            $updateParams = array_merge($updateParams, [
                'mode' => $request->get('mode') ?? 'fullscreen',
                'ori' => $request->get('ori') ?? 'center',
                'screen' => $request->get('screen') ?? 0,
            ]);
        }

        /** @var AdPlanTime $adPlanTime */
        $adPlanTime->fill($updateParams)->save();

        activity('update_ad_plan_time')
            ->causedBy($this->user())
            ->performedOn($adPlanTime)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $updateParams])
            ->log('编辑广告方案排期');

        return $this->response->noContent();
    }
}
