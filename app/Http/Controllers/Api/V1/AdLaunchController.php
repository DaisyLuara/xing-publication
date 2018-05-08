<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AdLaunch;
use App\Transformers\AdLaunchTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdLaunchController extends Controller
{
    public function index(AdLaunch $adLaunch)
    {
        $query = $adLaunch->query();
        $adLaunch = $query->orderBy('date', 'desc')->paginate(10);
        return $this->response->paginator($adLaunch, new AdLaunchTransformer());
    }

    public function query(Request $request, AdLaunch $adLaunch)
    {
        $start_date = $request->has('start_date') ? $request->start_date : Carbon::now()->addDay(-7)->toDateString();
        $end_date = $request->has('end_date') ? $request->end_date : Carbon::now()->toDateString();

        $query = $adLaunch->query();

        if ($request->has('atid')) {
            $query->where('atid', '=', $request->atid);
        }

        if ($request->has('atiid')) {
            $query->where('atiid', '=', $request->atiid);
        }

        if ($request->has('atid')) {
            $query->where('atid', '=', $request->atid);
        }

        if ($request->has('areaid')) {
            $query->where('areaid', '=', $request->areaid);
        }

        if ($request->has('marketid')) {
            $query->where('marketid', '=', $request->marketid);
        }

        $adLaunch = $query->whereRaw("str_to_date(date,'%Y-%m-%d') between '$start_date' and '$end_date'")
            ->orderBy('date', 'desc')
            ->paginate(10);

        return $this->response->paginator($adLaunch, new AdLaunchTransformer());
    }
}
