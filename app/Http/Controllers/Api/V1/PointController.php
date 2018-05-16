<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PointRequest;
use App\Models\Point;
use App\Transformers\PointTransformer;
use DB;

class PointController extends Controller
{
    public function map(PointRequest $request, Point $point)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $distance = $request->distance ? $request->distance : 1;

        $query = $point->query();
        $points = $query->join('face_count_log', 'avr_official.oid', '=', 'face_count_log.oid')
            ->selectRaw("sum(looknum) as count,lat,lng,avr_official.oid")
            ->whereRaw("ACOS(SIN(($lat * 3.1415) / 180) * SIN((lat * 3.1415) / 180) + COS(($lat * 3.1415) / 180) * COS((lat * 3.1415) / 180) *COS(($lng * 3.1415) / 180 - (lng * 3.1415) / 180)) * 6380 <= $distance")
            ->where('face_count_log.belong', '=', 'all')
            ->groupBy('avr_official.oid')
            ->get();

        return $this->response->collection($points, new PointTransformer());

    }
}
