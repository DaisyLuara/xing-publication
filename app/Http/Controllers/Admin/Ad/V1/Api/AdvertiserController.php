<?php

namespace App\Http\Controllers\Admin\Ad\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Transformer\AdvertiserTransformer;
use App\Http\Controllers\Admin\Ad\V1\Request\AdvertiserRequest;
use App\Http\Controllers\Admin\Ad\V1\Models\Advertiser;
use App\Http\Controllers\Controller;
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
        $data = $request->all();
        $atiids = $request->atiids;
        unset($data['atiids']);

        $query = $advertiser->query();
        foreach ($atiids as $atiid) {
            $query->where('atiid', '=', $atiid)->update($data);
        }
        return $this->response->noContent();
    }
}
