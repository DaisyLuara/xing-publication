<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AdTradeRequest;
use App\Models\AdTrade;
use App\Transformers\AdTradeTransformer;
use Illuminate\Http\Request;

class AdTradeController extends Controller
{
    public function index(Request $request, AdTrade $adTrade)
    {
        $query = $adTrade->query();
        $adTrade = $query->orderBy('atid', 'desc')->paginate(10);
        return $this->response->paginator($adTrade, new AdTradeTransformer());
    }

    public function store(AdTradeRequest $request, AdTrade $adTrade)
    {
        $data = $request->all();
        $names = explode(PHP_EOL, $request->name);
        unset($data['name']);

        $query = $adTrade->query();
        foreach ($names as $name) {
            $query->create(array_merge(['name' => $name, 'date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $data));
        }
        return $this->response->noContent();
    }

    public function update(AdTradeRequest $request, AdTrade $adTrade)
    {
        $data = $request->all();
        $atids = $request->atids;
        unset($data['atids']);

        $query = $adTrade->query();
        foreach ($atids as $atid) {
            $query->where('atid', '=', $atid)->update($data);
        }
        return $this->response->noContent();
    }
}
