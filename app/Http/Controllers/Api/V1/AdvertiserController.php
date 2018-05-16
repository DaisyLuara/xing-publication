<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AdvertiserRequest;
use App\Models\Advertiser;
use App\Transformers\AdvertiserTransformer;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    public function index(Request $request, Advertiser $advertiser)
    {
        $query = $advertiser->query();
        if ($request->ad_trade_id) {
            $query->where('atid', '=', $request->ad_trade_id);
        }
        $advertiser = $query->orderBy('atiid', 'desc')->paginate(10);
        return $this->response->paginator($advertiser, new AdvertiserTransformer());
    }

    public function store(AdvertiserRequest $request, Advertiser $advertiser)
    {
        if (env('APP_ENV') != 'production') {
            return $this->response->noContent();
        }

        $data = $request->all();
        $names = explode(PHP_EOL, $request->name);
        unset($data['name']);

        $query = $advertiser->query();
        foreach ($names as $name) {
            $query->create(array_merge(['name' => $name, 'date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $data));
        }

        return $this->response->noContent();
    }

    public function update(AdvertiserRequest $request, Advertiser $advertiser)
    {
        if (env('APP_ENV') != 'production') {
            return $this->response->noContent();
        }

        $data = $request->all();
        $atiids = $request->atiids;

        $query = $advertiser->query();
        foreach ($atiids as $atiid) {
            $query->where('atiid', '=', $atiid)->update($data);
        }
        return $this->response->noContent();
    }
}
