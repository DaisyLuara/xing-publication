<?php

namespace App\Http\Controllers\Api\V1;

use App\Transformers\AreaTransformer;
use App\Transformers\MarketTransformer;
use App\Transformers\PointTransformer;
use App\Transformers\ProjectLaunchTplTransformer;
use Illuminate\Http\Request;
use App\Models\Market;
use App\Models\Area;
use App\Models\Point;
use App\Models\ProjectLaunchTpl;

class QueryController extends Controller
{
    public function areaQuery(Request $request, Area $area)
    {
        $query = $area->query();
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $areas = $query->get();

        return $this->response->collection($areas, new AreaTransformer());
    }

    public function marketQuery(Request $request, Market $market)
    {
        $query = $market->query();
        $markets = collect();
        if (!$request->name && !$request->area_id) {
            return $this->response->collection($markets, new AreaTransformer());
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->area_id) {
            $query->where('areaid', '=', $request->area_id);
        }

        $markets = $query->get();

        return $this->response->collection($markets, new MarketTransformer());
    }

    public function pointQuery(Request $request, Point $point)
    {
        $query = $point->query();
        $points = collect();
        if (!$request->name && !$request->market_id) {
            return $this->response->collection($points, new AreaTransformer());
        }
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->market_id) {
            $query->where('marketid', '=', $request->market_id);
        }

        $points = $query->get();

        return $this->response->collection($points, new PointTransformer());
    }

    public function launchTplQuery(Request $request, ProjectLaunchTpl $projectLaunchTpl)
    {
        $query = $projectLaunchTpl->query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $templates = $query->where('oid', '=', 0)->get();

        return $this->response->collection($templates, new ProjectLaunchTplTransformer());

    }

}
