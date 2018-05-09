<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Advertiser;
use App\Transformers\AdvertiserTransformer;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    public function index(Request $request, Advertiser $advertiser)
    {
        $query = $advertiser->query();
        if($request->ad_trade_id){
            $query->where('atid','=',$request->ad_trade_id);
        }
        $advertiser = $query->orderBy('atiid', 'desc')->paginate(10);
        return $this->response->paginator($advertiser, new AdvertiserTransformer());
    }

}
