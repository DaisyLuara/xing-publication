<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class FaceCountController extends Controller
{
    public function getTenUser()
    {
        $startDate = Carbon::now()->addDay(-12)->toDateString();
        $endDate = Carbon::now()->addDay(-5)->toDateString();
        $faceCount = DB::connection('ar')->table('face_count_log as fcl')
            ->join('admin_per_oid as apo', 'fcl.oid', '=', 'apo.oid')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit(10)
            ->selectRaw("  apo.uid as uid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();
        $data = [];
        $faceCount->each(function ($item) use (&$data) {
            $data[] = [
                'uid' => $item->uid,
                'pointName' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'looknum' => $item->looknum
            ];
        });
        return $data;
    }
}
