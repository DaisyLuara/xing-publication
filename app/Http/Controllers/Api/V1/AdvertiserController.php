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
        if($request->atid){
            $query->where('atid','=',$request->atid);
        }
        $advertiser = $query->orderBy('atiid', 'desc')->paginate(10);
        return $this->response->paginator($advertiser, new AdvertiserTransformer());
    }

}
