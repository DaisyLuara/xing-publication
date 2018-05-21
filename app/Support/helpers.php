<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17-5-24
 * Time: 下午5:41
 */

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

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
        if (!$user->isAdmin()) {
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
    function handPointQuery(Request $request, Builder $builder, $arUserID, $projectName, $selectByAlias = true)
    {
        $table = $builder->getModel()->getTable();
        //查询时间范围
        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $builder->whereRaw("date_format($table.date, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate' ");
        }

        //按节目搜索 默认搜索所有节目
        if ($selectByAlias) {
            $alias = $request->alias ? $request->alias : 'all';
            $builder->where('belong', '=', $alias);
        } else {
            $builder->join('ar_product_list', 'ar_product_list.versionname', '=', "$table.$projectName");
        }

        //按指标查询
        if ($request->index) {
            $builder->selectRaw("sum(" . $request->index . ") as count");
        }

        //按账号查询
        if ($arUserID) {
            $builder->join('admin_per_oid', 'admin_per_oid.oid', '=', "$table.oid")
                ->where('admin_per_oid.uid', '=', $arUserID);
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
    }
}
