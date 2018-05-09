<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AdLaunchRequest;
use App\Models\AdLaunch;
use App\Models\Mock\AdLaunchLocal;
use App\Transformers\AdLaunchTransformer;

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

    //测试环境 使用 本地数据更新
    public function store(AdLaunchRequest $request, AdLaunchLocal $adLaunchLocal)
    {
        $launch = $request->all();
        $query = $adLaunchLocal->query();

        $oids = $launch['oids'];
        unset($launch['oids']);

        foreach ($oids as $oid) {
            $query->create(array_merge(['oid' => $oid, 'date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $launch));
        }

        return $this->response->noContent();
    }

    public function update(AdLaunchRequest $request, AdLaunchLocal $adLaunchLocal)
    {

        $launch = $request->all();

        $aoids = $launch['aoids'];
        unset($launch['aoids']);
        unset($launch['oid']);

        foreach ($aoids as $aoid) {
            $query = $adLaunchLocal->query();
            $query->where(['aoid' => $aoid])->update($launch);
        }

        return $this->response->noContent();
    }
}
