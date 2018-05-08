<?php

namespace App\Http\Controllers\Api\V1;

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
}
