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

        //广告素材ID 对广告素材进行判断
        $aids = $request->get('aids');
        $select_aids = Advertisement::query()
            ->whereIn('aid', $aids)
//            ->where('atid','=',$params['atid'])
            ->pluck('aid')->toArray();

        if (array_diff($aids, $select_aids)) {
            abort(422, '请选择正确的广告素材');
        }

        if (!$request->get('icon')) {
            $params['icon'] = 'http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png';
        }

        /** @var AdPlan $adPlan */
        $adPlan->fill(array_merge([
            'date' => date('Y-m-d H:i:s'),
            'clientdate' => time() * 1000],
            $params))->save();
        $this->syncAdvertisement($request, $adPlan, $aids);

        return $this->response->noContent();
    }

    /**
     * 批量编辑
     * @param AdPlanRequest $request
     * @param AdPlan $adPlan
     * @return Response
     */
    public function updateBatch(AdPlanRequest $request, AdPlan $adPlan): Response
    {
        $params = $request->all();

        if ($params['type'] !== $adPlan->type) {
            abort(422, '类型不可修改');
        }
        unset($params['type']);

        //广告素材ID 对广告素材进行判断
        $aids = $request->get('aids');
        $select_aids = Advertisement::query()
            ->whereIn('aid', $aids)
//            ->where('atid','=',$params['atid'])
            ->pluck('aid')->toArray();

        if (array_diff($aids, $select_aids)) {
            abort(422, '请选择正确的广告素材');
        }

        if (!$request->get('icon')) {
            $params['icon'] = 'http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png';
        }

        /** @var AdPlan $adPlan */
        $adPlan->fill(array_merge([
            'date' => date('Y-m-d H:i:s'),
            'clientdate' => time() * 1000],
            $params))->save();
        $this->syncAdvertisement($request, $adPlan, $aids);

        return $this->response->noContent();
    }


    private function syncAdvertisement(Request $request, AdPlan $adPlan, array $aids): void
    {
        $advertisements = [];

        $updateParams = [
            'cdshow' => $request->get('cdshow'),
            'ktime' => $request->get('ktime'),
            'shm' => $request->get('shm') ? (int)Carbon::parse($request->get('shm'), 'UTC')->format('Hi') : 0,
            'ehm' => $request->get('ehm') ? (int)Carbon::parse($request->get('ehm'), 'UTC')->format('Hi') : 0,
            'date' => date('Y-m-d H:i:s'),
            'clientdate' => time() * 1000,
        ];

        if ($adPlan->type === AdPlan::TYPE_BID_SCREEN) {
            array_merge($updateParams, [
                'mode' => $request->get('mode') ?? 'fullscreen',
                'ori' => $request->get('ori') ?? 'center',
                'screen' => $request->get('screen') ?? 0,
            ]);
        }

        foreach ($aids as $aid) {
            $advertisements [$aid] = $updateParams;
        }

        $adPlan->advertisements()->sync($advertisements);
    }

    /**
     * 一般编辑
     * @param AdPlanRequest $request
     * @param AdPlan $adPlan
     * @return Response
     */
    public function update(AdPlanRequest $request, AdPlan $adPlan): Response
    {
        /** @var AdPlan $adPlan */
        $adPlan->fill([
            'name' => $request->get('name'),
            'icon' => $request->get('icon') ?? 'http://image.xingstation.cn/1007/image/393_511_941_578_ic_launcher.png',
            'info' => $request->get('info'),
            'atid' => $request->get('atid'),
            'date' => date('Y-m-d H:i:s'),
            'clientdate' => time() * 1000,
        ])->save();

        return $this->response->noContent();
    }
}
