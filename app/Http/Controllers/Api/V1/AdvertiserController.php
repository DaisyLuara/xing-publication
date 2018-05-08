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
        $advertiser = $query->orderBy('atiid', 'desc')->paginate(10);
        return $this->response->paginator($advertiser, new AdvertiserTransformer());
    }

    public function query(Request $request, Advertiser $advertiser)
    {
        $query = $advertiser->query();
        $advertiser = collect();
        if (!$request->name && !$request->atid) {
            return $this->response->collection($advertiser, new AdvertiserTransformer());
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('atid')) {
            $query->where('atid', '=', $request->atid);
        }
        $advertiser = $query->get();
        return $this->response->paginator($advertiser, new AdvertiserTransformer());
    }
}
