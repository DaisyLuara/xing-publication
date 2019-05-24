<?php

namespace App\Http\Controllers\Admin\Ad\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Transformer\AdTradeTransformer;
use App\Http\Controllers\Admin\Ad\V1\Request\AdTradeRequest;
use App\Http\Controllers\Admin\Ad\V1\Models\AdTrade;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class AdTradeController extends Controller
{
    public function index(Request $request, AdTrade $adTrade): Response
    {
        $query = $adTrade->query();

        if ($request->get('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $adTrades = $query->orderBy('atid', 'desc')->paginate(10);

        return $this->response->paginator($adTrades, new AdTradeTransformer());
    }

    public function store(AdTradeRequest $request, AdTrade $adTrade): Response
    {
        $data = $request->all();
        $names = explode(PHP_EOL, $request->get('name'));
        unset($data['name']);

        $query = $adTrade->query();
        foreach ($names as $name) {
            $query->create(array_merge($data, ['name' => $name]));
        }
        return $this->response->noContent();
    }

    public function update(AdTradeRequest $request, AdTrade $adTrade): Response
    {
        $data = $request->all();
        $atids = $request->get('atids');
        unset($data['atids']);

        $query = $adTrade->query();
        foreach ($atids as $atid) {
            $query->where('atid', '=', $atid)->update($data);
        }
        return $this->response->noContent();
    }
}
