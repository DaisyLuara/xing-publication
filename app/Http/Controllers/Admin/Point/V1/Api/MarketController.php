<?php

namespace App\Http\Controllers\Admin\Point\V1\Api;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Transformer\MarketTransformer;
use App\Http\Controllers\Controller;

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

}
