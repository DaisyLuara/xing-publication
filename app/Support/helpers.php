<?php

use App\Http\Controllers\Admin\Face\V1\Models\FaceCount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

/**
 *求两个已知经纬度之间的距离,单位为千米
 * @param lng1,lng2 经度
 * @param lat1,lat2 纬度
 * @return float 距离，单位千米
 **/
if (!function_exists('distance')) {
    function distance($lng1, $lat1, $lng2, $lat2)
    {
        //将角度转为弧度
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);

        $a = $radLat1 - $radLat2;//两纬度之差,纬度<90
        $b = $radLng1 - $radLng2;//两经度之差纬度<180
        return 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378137;
    }
}

if (!function_exists('getArUserID')) {
    function getArUserID(User $user, Request $request)
    {
        //BD 返回自己的 ar_user_id
        if ($user->isUser()) {
            return $user->ar_user_id;
        }

        if ($request->ar_user_id) {
            return $request->ar_user_id;
        }

        return 0;
    }
}


if (!function_exists('formatClientDate')) {
    function formatClientDate($clientRate)
    {
        return date('Y-m-d H:i:s', $clientRate / 1000);
    }
}

/**
 *求两个已知经纬度之间的距离,单位为千米
 * @param lng1,lng2 经度
 * @param lat1,lat2 纬度
 * @return float 距离，单位千米
 **/
if (!function_exists('distance')) {
    function distance($lng1, $lat1, $lng2, $lat2)
    {
        //将角度转为弧度
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);

        $a = $radLat1 - $radLat2;//两纬度之差,纬度<90
        $b = $radLng1 - $radLng2;//两经度之差纬度<180
        return 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
    }
}

/**
 * 处理点位查询
 */
if (!function_exists('handPointQuery')) {
    function handPointQuery(Request $request, Builder $builder, $arUserID, bool $selectPoint = false)
    {
        $table = $builder->getModel()->getTable();
        //查询时间范围
        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $builder->whereRaw("date_format($table.date, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate' ");
        }

        //按指标查询
        if ($request->index) {
            $builder->selectRaw("sum(" . $request->index . ") as count");
        }

        //BD
        if ($arUserID) {
            $builder->where('avr_official.bd_uid', '=', $arUserID);
        }

        //按场景查询
        if ($request->scene_id) {
            $builder->where('avr_official.sid', '=', $request->scene_id);
        }

        //按区域查询
        if ($request->area_id) {
            $builder->where('avr_official.areaid', '=', $request->area_id);
        }

        //按商场查询
        if ($request->market_id) {
            $builder->where('avr_official.marketid', '=', $request->market_id);
        }

        //按点位查询
        if ($request->point_id) {
            $builder->where('avr_official.oid', '=', $request->point_id);
        }

        $builder->join('avr_official', 'avr_official.oid', '=', "$table.oid")
            ->join('avr_official_market', 'avr_official_market.marketid', '=', 'avr_official.marketid')
            ->join('avr_official_area', 'avr_official_area.areaid', '=', 'avr_official.areaid');

        if ($selectPoint) {
            $builder->selectRaw("avr_official.name as point_name,avr_official_market.name as market_name,avr_official_area.name as area_name");
            $builder->selectRaw("avr_official.oid as point_id,avr_official_market.marketid as market_id,avr_official_area.areaid as area_id");
        }
    }
}

/**
 * @param $startDate
 * @param $endDate
 * @param Builder $faceCountQuery
 * @return mixed
 */
if (!function_exists('getPointsByScene')) {
    function getPointsByScene($startDate, $endDate)
    {
        $faceCountQuery = FaceCount::query();
        $table = $faceCountQuery->getModel()->getTable();
        return $faceCountQuery->join('avr_official', 'avr_official.oid', '=', "$table.oid")
            ->whereRaw(" date_format($table.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->selectRaw("count(distinct($table.oid)) as total,sid")
            ->groupBy('sid')
            ->get();
    }
}

/**
 * @param $startDate
 * @param $endDate
 * @param array $scene
 * @return array
 */
if (!function_exists('getFaceCountByScene')) {

    function getFaceCountByScene($startDate, $endDate, $scene = [])
    {

        $faceCountQuery = FaceCount::query();
        $table = $faceCountQuery->getModel()->getTable();
        if ($scene['name'] == 'other') {
            $faceCountQuery->whereNotIn('ao.sid', $scene['sid']);
        } else {
            $faceCountQuery->where('ao.sid', '=', $scene['sid']);
        }

        $faceCount = $faceCountQuery->join('avr_official as ao', "$table.oid", '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format($table.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->groupBy("$table.oid")
            ->orderBy('looknum')
            ->limit($scene['limit'])
            ->selectRaw("  ao.bd_uid as uid,as.realname as userName,aos.sid as sceneId,aos.name as sceneName,$table.oid as oid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();

        $data = [];
        $faceCount->each(function ($item, $index) use (&$data, $startDate, $endDate, $scene) {
            if (round($item->looknum / 7, 0) < $scene['avg']) {
                $data[] = [
                    'ar_user_id' => $item->uid,
                    'ar_user_name' => $item->userName,
                    'point_id' => $item->oid,
                    'point_name' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                    'scene_id' => $item->sceneId,
                    'scene_name' => $item->sceneName,
                    'looknum_average' => round($item->looknum / 7, 0),
                    'ranking' => $index + 1,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'date' => Carbon::now()->toDateString(),
                ];
            }
        });
        return $data;
    }
}