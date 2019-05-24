<?php

namespace App\Http\Controllers\Admin\Ad\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Models\Advertisement;
use App\Http\Controllers\Admin\Ad\V1\Transformer\AdPlanTransformer;
use App\Http\Controllers\Admin\Ad\V1\Request\AdPlanRequest;
use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class AdPlanController extends Controller
{
    /**
     * @param Request $request
     * @param AdPlan $adPlan
     * @return Response
     */
    public function index(Request $request, AdPlan $adPlan): Response
    {
        $query = $adPlan->query();

        //大屏 or 小屏
        if ($request->get('type')) {
            $query->where('type', '=', $request->get('type'));
        }

        //广告行业
        if ($request->get('ad_trade_id')) {
            $query->where('atid', '=', $request->get('ad_trade_id'));
        }

        //广告方案 name
        if ($request->get('ad_plan_name')) {
            $query->where('name', 'like', '%' . $request->get('ad_plan_name') . '%');
        }

        //指定某个方案ID
        if ($request->get('ad_plan_id')) {
            $query->where('atiid', '=', $request->get('ad_plan_id'));
        }

        //状态
        if ($request->get('visiable') !== null) {
            $query->where('visiable', '=', $request->get('visiable'));
        }

        //是否唯一
        if ($request->get('only') !== null) {
            $query->where('only', '=', $request->get('only'));
        }

        $adPlans = $query->orderBy('atiid', 'desc')->paginate(10);
        return $this->response->paginator($adPlans, new AdPlanTransformer());
    }

    public function show(AdPlan $adPlan): Response
    {
        return $this->response->item($adPlan, new AdPlanTransformer());
    }

    /**
     * @param AdPlanRequest $request
     * @param AdPlan $adPlan
     * @return Response
     */
    public function store(AdPlanRequest $request, AdPlan $adPlan): Response
    {
        $params = $request->all();

        if (!$request->get('icon')) {
            $params['icon'] = 'http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png';
        }

        $addParams = [];
        if ($this->user->z) {
            $addParams['z'] = $this->user->z;
        }

        /** @var AdPlan $adPlan */
        $adPlan->fill(array_merge($params, $addParams))->save();

        return $this->response->noContent();
    }

    /**
     * 编辑
     * @param AdPlanRequest $request
     * @param AdPlan $adPlan
     * @return Response
     */
    public function update(AdPlanRequest $request, AdPlan $adPlan): Response
    {
        $params = $request->all();

        if (!$request->get('icon')) {
            $params['icon'] = 'http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png';
        }

        /** @var AdPlan $adPlan */
        $adPlan->fill($params)->save();

        return $this->response->noContent();
    }
}
