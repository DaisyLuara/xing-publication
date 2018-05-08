<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Market;
use Illuminate\Http\Request;
use App\Transformers\MarketTransformer;

class MarketController extends Controller
{
    public function query(Request $request, Market $market)
    {
        $query = $market->query();
        $markets = collect();
        if (!$request->name && !$request->area_id) {
            return $this->response->collection($markets, new AreaTransformer());
        }
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->area_id) {
            $query->where('areaid', '=', $request->area_id);
        }

        $markets = $query->get();

        return $this->response->collection($markets, new MarketTransformer());
    }

}
