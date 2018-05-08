<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AdLaunch;
use App\Transformers\AdLaunchTransformer;
use Illuminate\Http\Request;

class AdLaunchController extends Controller
{
    public function index(Request $request, AdLaunch $adLaunch)
    {
        $query = $adLaunch->query();

        if ($request->has('atid')) {
            $query->where('atid', '=', $request->atid);
        }

        if ($request->has('atiid')) {
            $query->where('atiid', '=', $request->atiid);
        }

        if ($request->has('aid')) {
            $query->where('aid', '=', $request->aid);
        }

        if ($request->has('area_id')) {
            $query->where('areaid', '=', $request->area_id);
        }

        if ($request->has('market_id')) {
            $query->where('marketid', '=', $request->market_id);
        }

        if ($request->has('oid')) {
            $query->where('oid', '=', $request->oid);
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
