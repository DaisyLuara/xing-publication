<?php

namespace App\Http\Controllers\Api\V1;

use App\Transformers\AreaTransformer;
use App\Transformers\MarketTransformer;
use App\Transformers\PointTransformer;
use App\Transformers\ProjectLaunchTplTransformer;
use App\Transformers\AdTradeTransformer;
use App\Transformers\AdvertiserTransformer;
use App\Transformers\AdvertisementTransformer;
use Illuminate\Http\Request;
use App\Models\Market;
use App\Models\Area;
use App\Models\Point;
use App\Models\ProjectLaunchTpl;
use App\Models\AdTrade;
use App\Models\Advertiser;
use App\Models\Advertisement;

class QueryController extends Controller
{
    public function areaQuery(Request $request, Area $area)
    {
        $query = $area->query();
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $areas = $query->where('areaid', '>', 0)->get();

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

        $markets = $query->where('marketid', '>', 0)->get();

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


    public function adTradeQuery(Request $request, AdTrade $adTrade)
    {
        $query = $adTrade->query();
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $adTrade = $query->get();
        return $this->response->collection($adTrade, new AdTradeTransformer());
    }

    public function advertiserQuery(Request $request, Advertiser $advertiser)
    {
        $query = $advertiser->query();
        $advertiser = collect();
        if (!$request->name && !$request->ad_trade_id) {
            return $this->response->collection($advertiser, new AdvertiserTransformer());
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->ad_trade_id) {
            $query->where('atid', '=', $request->ad_trade_id);
        }
        $advertiser = $query->get();
        return $this->response->collection($advertiser, new AdvertiserTransformer());
    }

    public function advertisementQuery(Request $request, Advertisement $advertisement)
    {
        $query = $advertisement->query();
        $advertisement = collect();
        if (!$request->advertiser_id && !$request->name) {
            return $this->response->collection($advertisement, new AdvertisementTransformer());
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->advertiser_id) {
            $query->where('atiid', '=', $request->advertiser_id);
        }
        $advertisement = $query->get();
        return $this->response->collection($advertisement, new AdvertisementTransformer());
    }

}
