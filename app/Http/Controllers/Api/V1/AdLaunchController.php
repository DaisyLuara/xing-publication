<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AdLaunch;
use App\Transformers\AdLaunchTransformer;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\AdLaunchRequest;

class AdLaunchController extends Controller
{
    public function index(AdLaunchRequest $request, AdLaunch $adLaunch)
    {
        $query = $adLaunch->query();

        if ($request->has('adTrade_id')) {
            $query->where('atid', '=', $request->adTrade_id);
        }

        if ($request->has('advertiser_id')) {
            $query->where('atiid', '=', $request->advertiser_id);
        }

        if ($request->has('advertisement_id')) {
            $query->where('aid', '=', $request->advertisement_id);
        }

        if ($request->has('area_id')) {
            $query->where('areaid', '=', $request->area_id);
        }

        if ($request->has('market_id')) {
            $query->where('marketid', '=', $request->market_id);
        }

        if ($request->has('point_id')) {
            $query->where('oid', '=', $request->point_id);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereRaw("str_to_date(date,'%Y-%m-%d') between '$start_date' and '$end_date'");
        }

        $adLaunch = $query->orderBy('date', 'desc')->paginate(10);
        return $this->response->paginator($adLaunch, new AdLaunchTransformer());
    }
}
