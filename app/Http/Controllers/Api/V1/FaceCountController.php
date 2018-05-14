<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\FaceCount;
use App\Transformers\FaceCountDetailTransformer;
use App\Transformers\FaceCountTransformer;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class FaceCountController extends Controller
{
    public function index(Request $request, FaceCount $faceCount)
    {
        $query = $this->queryInit($request, $faceCount->query());
        $default = $this->getDefaultParams($request);

        $faceCount = $query->where('belong', '=', $default['alias'])
            ->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '" . $default['startDate'] . "' AND '" . $default['endDate'] . "'")
            ->selectRaw('sum(looknum) as looknum ,sum(playernum) as playernum ,sum(lovenum) as lovenum,sum(outnum) as outnum,sum(scannum) as scannum')
            ->first();

        return $this->response->item($faceCount, new FaceCountTransformer());
    }

    public function detail(Request $request, FaceCount $faceCount)
    {
        $query = $this->queryInit($request, $faceCount->query());
        $default = $this->getDefaultParams($request);

        $faceCount = $query->whereRaw("str_to_date(date, '%Y-%m-%d') BETWEEN '" . $default['startDate'] . "' AND '" . $default['endDate'] . "'")
            ->where('belong', '=', $default['alias'])
            ->selectRaw("date_format(date,'%Y-%m-%d') as date,sum(" . $default['type'] . ") as count")
            ->groupBy(DB::raw("date_format(date,'%Y-%m-%d')"))
            ->get();

        return $this->response->collection($faceCount, new FaceCountDetailTransformer());
    }

    private function queryInit($request, $query)
    {
        $user = $this->user();
        $arUserId = getArUserID($user, $request);
        if ($arUserId) {
            $query->whereHas('pointArUser', function ($q) use ($arUserId) {
                $q->where('uid', '=', $arUserId);
            });
        }

        if ($request->has('point_id')) {
            $query->where('oid', '=', $request->point_id);
        }

        return $query;

    }

    private function getDefaultParams($request)
    {
        $startDate = $request->has('start_date') ? $request->start_date : Carbon::now()->addDays(-7)->toDateString();
        $endDate = $request->has('end_date') ? $request->end_date : Carbon::now()->toDateString();
        $alias = $request->has('alias') ? $request->alias : 'all';
        $type = $request->has('type') ? $request->type : 'looknum';

        return compact('startDate', 'endDate', 'alias', 'type');
    }

    public function getTenUser($date)
    {
        DB::connection('ar')->table('face_count_log as fcl')
            ->join('admin_per_oid as apo', 'fcl.oid', '=', 'apo.oid')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$date' and '$date' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->orderBy('looknum')
            ->limit(10)
            ->selectRaw("  apo.uid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,looknum")
            ->get();
    }
}
