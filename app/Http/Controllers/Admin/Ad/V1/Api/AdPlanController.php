<?php

namespace App\Http\Controllers\Admin\Ad\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Transformer\AdPlanTransformer;
use App\Http\Controllers\Admin\Ad\V1\Request\AdPlanRequest;
use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdPlanController extends Controller
{
    public function index(Request $request, AdPlan $advertiser)
    {
        $query = $advertiser->query();
        if ($request->ad_trade_id) {
            $query->where('atid', '=', $request->ad_trade_id);
        }
        $advertiser = $query->orderBy('atiid', 'desc')->paginate(10);
        return $this->response->paginator($advertiser, new AdPlanTransformer());
    }

    public function store(AdPlanRequest $request, AdPlan $advertiser)
    {
        $data = $request->all();
        $names = explode(PHP_EOL, $request->name);
        unset($data['name']);

        $query = $advertiser->query();
        foreach ($names as $name) {
            $query->create(array_merge(['name' => $name, 'date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $data));
        }

        return $this->response->noContent();
    }

//    public function update(AdPlanRequest $request, AdPlan $advertiser)
//    {
//        $data = $request->all();
//        $atiids = $request->atiids;
//        unset($data['atiids']);
//
//        $query = $advertiser->query();
//        foreach ($atiids as $atiid) {
//            $query->where('atiid', '=', $atiid)->update($data);
//        }
//        return $this->response->noContent();
//    }
}
