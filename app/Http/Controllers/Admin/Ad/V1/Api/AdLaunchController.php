<?php

namespace App\Http\Controllers\Admin\Ad\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Transformer\AdLaunchTransformer;
use App\Http\Controllers\Admin\Ad\V1\Request\AdLaunchRequest;
use App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch;
use App\Http\Controllers\Controller;

class AdLaunchController extends Controller
{
    public function index(AdLaunchRequest $request, AdLaunch $adLaunch)
    {
        $query = $adLaunch->query();

        if ($request->has('ad_trade_id')) {
            $query->where('atid', '=', $request->ad_trade_id);
        }

        if ($request->has('advertiser_id')) {
            $query->where('atiid', '=', $request->advertiser_id);
        }

        if ($request->has('advertisement_id')) {
            $query->where('aid', '=', $request->advertisement_id);
        }

        if ($request->has('area_id')) {
            $query->where('areaid', '=', $request->area_id);
        }

        if ($request->has('market_id')) {
            $query->where('marketid', '=', $request->market_id);
        }

        if ($request->has('point_id')) {
            $query->where('oid', '=', $request->point_id);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereRaw("str_to_date(date,'%Y-%m-%d') between '$startDate' and '$endDate'");
        }

        $adLaunch = $query->whereHas('advertisement')
            ->orderBy('date', 'desc')
            ->paginate(10);
        return $this->response->paginator($adLaunch, new AdLaunchTransformer());
    }

    public function store(AdLaunchRequest $request, AdLaunch $adLaunch)
    {
        $launch = $request->all();
        $query = $adLaunch->query();

        $oids = $launch['oids'];
        unset($launch['oids']);

        foreach ($oids as $oid) {
            $query->create(array_merge(['oid' => $oid, 'date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $launch));
        }

        activity('ad_launch')->on($adLaunch)->withProperties($request->all())->log('批量增加广告投放');

        return $this->response->noContent();
    }

    public function update(AdLaunchRequest $request, AdLaunch $adLaunch)
    {
        $launch = $request->all();
        $aoids = $launch['aoids'];

        unset($launch['aoids']);
        unset($launch['oid']);

        foreach ($aoids as $aoid) {
            $query = $adLaunch->query();
            $query->where(['aoid' => $aoid])->update($launch);
        }

        activity('ad_launch')->on($adLaunch)->withProperties($request->all())->log('批量修改广告投放');

        return $this->response->noContent();
    }
}