<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\AreaTransformer;
use App\Models\Area;

class AreaController extends Controller
{
    public function query(Request $request, Area $area)
    {
        $query = $area->query();
        $areas = collect();
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
            $areas = $query->get();
        }
        return $this->response->collection($areas, new AreaTransformer());
    }

}
