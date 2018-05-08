<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\PointTransformer;
use App\Models\Point;

class PointController extends Controller
{
    public function query(Request $request, Point $point)
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
}
