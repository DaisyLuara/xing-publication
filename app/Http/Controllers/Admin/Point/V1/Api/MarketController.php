<?php

namespace App\Http\Controllers\Admin\Point\V1\Api;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Request\MarketRequest;
use App\Http\Controllers\Admin\Point\V1\Transformer\MarketTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketController extends Controller
{

    public function index(Request $request, Market $market)
    {
        $query = $market->query();
        //场地名称
        if ($request->has('market_name')) {
            $query->where('name', 'like', '%' . $request->market_name . '%');
        }

        //区域
        if ($request->has('areaid')) {
            $query->where('areaid', '=', $request->areaid);
        }

        //场地类型
        if ($request->has('contract_type')) {
            $contractType = $request->contract_type;
            $query->whereHas('contract', function ($query) use ($contractType) {
                $query->where('type', '=', $contractType);
            });
        }

        //合作模式
        if ($request->has('contract_mode')) {
            $contractMode = $request->contract_mode;
            $query->whereHas('contract', function ($query) use ($contractMode) {
                $query->where('mode', '=', $contractMode);
            });
        }

        //场地权限
        if ($request->has('share_users')) {
            $shareUsers = explode(',', $request->share_users);
            $query->whereHas('share', function ($query) use ($shareUsers) {
                foreach ($shareUsers as $shareUser) {
                    $query->where("$shareUser", '=', 1);
                }
            });
        }

        $markets = $query->paginate(10);
        return $this->response->paginator($markets, new MarketTransformer());
    }

    public function show(Market $market)
    {
        return $this->response->item($market, new MarketTransformer());
    }

    public function store(MarketRequest $request, Market $market)
    {
        $market->fill($request->all())->saveOrFail();

        if ($request->has('contract')) {
            $market->contract()->create($request->contract);
        }

        if ($request->has('share')) {
            $market->share()->create($request->share);
        }

        return $this->response->item($market, new MarketTransformer());
    }

    public function update(MarketRequest $request, Market $market)
    {
        $market->update($request->all());
        if ($request->has('contract')) {
            $contract = $request->contract;
            if (isset($contract['marketid'])) {
                unset($contract['marketid']);
            }

            $market->contract()->getResults()->update($contract);
        }

        if ($request->has('share')) {
            $share = $request->share;
            if (isset($share['marketid'])) {
                unset($share['marketid']);
            }
            $market->share()->getResults()->update($share);
        }

        return $this->response->item($market, new MarketTransformer());
    }
}
