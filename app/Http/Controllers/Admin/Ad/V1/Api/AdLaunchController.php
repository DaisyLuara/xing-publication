<?php

namespace App\Http\Controllers\Admin\Ad\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Transformer\AdLaunchTransformer;
use App\Http\Controllers\Admin\Ad\V1\Request\AdLaunchRequest;
use App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Dingo\Api\Http\Response;

class AdLaunchController extends Controller
{
    /**
     * 广告方案投放列表
     * @param AdLaunchRequest $request
     * @param AdLaunch $adLaunch
     * @return Response
     */
    public function index(AdLaunchRequest $request, AdLaunch $adLaunch): Response
    {
        $query = $adLaunch->query();

        $query->whereHas('ad_plan', static function ($q) use ($request) {
            //大屏 or 小屏
            if ($request->get('type')) {
                $q->where('type', '=', $request->get('type'));
            }

            //广告行业
            if ($request->get('ad_trade_id')) {
                $q->where('atid', '=', $request->get('ad_trade_id'));
            }

            //广告方案 name
            if ($request->get('ad_plan_name')) {
                $q->where('name', 'like', '%' . $request->get('ad_plan_name') . '%');
            }
        });

        //广告方案
        if ($request->get('ad_plan_id')) {
            $query->where('atiid', '=', $request->get('ad_plan_id'));
        }

        //商场
        if ($request->get('market_id')) {
            $query->where('marketid', '=', $request->get('market_id'));
        }

        //点位
        if ($request->get('point_id')) {
            $query->where('oid', '=', $request->get('point_id'));
        }

        //节目
        if ($request->get('project_id')) {
            $query->where('piid', '=', $request->get('project_id'));
        }

        if ($request->get('start_date') && $request->get('end_date')) {
            $query->whereRaw("str_to_date(date,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}'");
        }

        $adLaunches = $query->orderBy('date', 'desc')->paginate(10);

        return $this->response->paginator($adLaunches, new AdLaunchTransformer());
    }

    /**
     * 广告方案投放
     * @param AdLaunchRequest $request
     * @param AdLaunch $adLaunch
     * @return \Dingo\Api\Http\Response
     */
    public function store(AdLaunchRequest $request, AdLaunch $adLaunch): Response
    {
        $launch = $request->all();
        $query = $adLaunch->query();

        $oids = $launch['oids'];
        unset($launch['oids']);

        if ($request->get('sdate')) {
            $launch['sdate'] = Carbon::parse($launch['sdate'])->timestamp;
        }
        if ($request->get('edate')) {
            $launch['edate'] = Carbon::parse($launch['edate'])->timestamp;
        }

        foreach ($oids as $oid) {
            $query->create(array_merge($launch, ['oid' => $oid]));
        }

        activity('ad_launch')->on($adLaunch)->withProperties($request->all())->log('批量增加广告投放');

        activity('create_ad_launch')
            ->causedBy($this->user())
            ->performedOn($adLaunch)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('批量新增广告投放');

        return $this->response->noContent();
    }

    /**
     * 广告投放的批量修改
     * @param AdLaunchRequest $request
     * @param AdLaunch $adLaunch
     * @return \Dingo\Api\Http\Response
     */
    public function update(AdLaunchRequest $request, AdLaunch $adLaunch): Response
    {

        $launch = $request->all();
        $aoids = $launch['aoids'];
        $keys = $launch['keys'];
        if (array_diff($keys, ['atiid', 'sdate', 'edate', 'visiable', 'only'])) {
            abort(422, '修改字段存在不允许修改字段');
        }

        unset($launch['aoids'], $launch['keys']);

        $update_params = [
            'date' => date('Y-m-d H:i:s'),
            'clientdate' => time() * 1000
        ];

        foreach ($keys as $key) {
            if (!isset($launch[$key])) {
                abort(422, '请传入参数 ' . $key);
            }

            if ($key === 'sdate' || $key === 'edate') {
                $update_params[$key] = Carbon::parse($launch[$key])->timestamp;
            } else {
                $update_params[$key] = $launch[$key];
            }
        }

        foreach ($aoids as $aoid) {
            $query = $adLaunch->query();
            $query->where(['aoid' => $aoid])->update($update_params);
        }

        activity('update_ad_launch')
            ->causedBy($this->user())
            ->performedOn($adLaunch)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('批量修改广告投放');

        return $this->response->noContent();
    }
}
