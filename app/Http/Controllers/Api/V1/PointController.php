<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\PointTransformer;

class PointController extends Controller
{
    public function index(Request $request, Point $point)
    {
        $query = $point->query();
        $points = $query->paginate(20);
        return $this->response->paginator($points, new PointTransformer());
    }

    public function userIndex()
    {

    }
}
