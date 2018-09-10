<?php

namespace App\Http\Controllers\Admin\Point\V1\Api;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Request\MarketRequest;
use App\Http\Controllers\Admin\Point\V1\Transformer\MarketTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class MarketController extends Controller
{

    public function index(Market $market)
    {
        $markets = $market->paginate(10);
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
