<?php

use App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCount;
use App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket;
use App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Common\V1\Request\ExportRequest;

/**
 *求两个已知经纬度之间的距离,单位为千米
 * @param lng1 ,lng2 经度
 * @param lat1 ,lat2 纬度
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

/**
 * 判断数组中是否全为true
 */
if (!function_exists('check_arr')) {
    function check_arr($rs)
    {
        foreach ($rs as $v) {
            if (!$v) {
                return false;
            }
        }

        return true;
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
 * @param lng1 ,lng2 经度
 * @param lat1 ,lat2 纬度
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
            $workday = $request->workday;
            $weekend = $request->weekend;
            $holiday = $request->holiday;
            $builder->join('xs_calendar', 'xs_calendar.clientdate', '=', "$table.clientdate");
            if ($workday == 1 && $weekend == 0 && $holiday == 0) {
                $builder->WhereRaw("xs_calendar.workday=1");
            } else if ($workday == 0 && $weekend == 1 && $holiday == 0) {
                $builder->WhereRaw("xs_calendar.weekend=1");
            } else if ($workday == 0 && $weekend == 0 && $holiday == 1) {
                $builder->WhereRaw("xs_calendar.holiday=1");
            } else if ($workday == 1 && $weekend == 1 && $holiday == 0) {
                $builder->whereRaw("(xs_calendar.workday=1 or xs_calendar.weekend=1)");
            } else if ($workday == 0 && $weekend == 1 && $holiday == 1) {
                $builder->whereRaw("(xs_calendar.weekend=1 or xs_calendar.holiday=1)");
            } else if ($workday == 1 && $weekend == 0 && $holiday == 1) {
                $builder->whereRaw("(xs_calendar.workday=1 or xs_calendar.holiday=1)");
            } else if ($workday == 1 && $weekend == 1 && $holiday == 1) {
                $builder->whereRaw("(xs_calendar.workday=1 or xs_calendar.weekend=1 or xs_calendar.holiday=1)");
            }

        }

        //按指标查询
        if ($request->index) {
            $indexes = explode(',', $request->index);
            foreach ($indexes as $index) {
                $builder->selectRaw("sum($index) as $index");
            }
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
            ->whereRaw(" date_format($table.date,'%Y-%m-%d') between '$startDate' and '$endDate' and ao.marketid<>15 and as.realname<>'颜镜店'")
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

if (!function_exists('getRand')) {

    function getRand($proArr)
    {
        $result = array();
        foreach ($proArr as $key => $val) {
            if (is_object($val)) {
                $val = get_object_vars($val);
            }
            $arr[$key] = $val['rate'];
        }
        // 概率数组的总概率
        $proSum = array_sum($arr);
        asort($arr);
        // 概率数组循环
        // 检查奖品容量
        foreach ($arr as $k => $v) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $v) {
                $result = $proArr[$k];
                break;
            } else {
                $proSum -= $v;
            }
        }
        return $result;
    }
}

if (!function_exists('ding_test')) {
    function ding_test()
    {
        $title = '杭州天气';
        $markdown = "#### 杭州天气  \n " .
            "> 9度，@1825718XXXX 西北风1级，空气良89，相对温度73%\n\n " .
            "> ![screenshot](http://i01.lw.aliimg.com/media/lALPBbCc1ZhJGIvNAkzNBLA_1200_588.png)\n" .
            "> ###### 10点20分发布 [天气](http://www.thinkpage.cn/) ";

        ding()->with('other')->markdown($title, $markdown);
    }
}

if (!function_exists('couponQrCode')) {
    function couponQrCode($code, $size = 200, $prefix = 'mini_qrcode_', WechatCouponBatch $wechatCouponBatch = null)
    {
        $cacheIndex = $prefix . $code;
        if (cache()->has($cacheIndex)) {
            return cache()->get($cacheIndex);
        }

        if ($wechatCouponBatch && $wechatCouponBatch->id) {
            /** @var \EasyWeChat\OpenPlatform\Application $app */
            $app = EasyWeChat::openPlatform();
            componentVerify($app);
            $official_account = getOfficialAccount($wechatCouponBatch->wechat_authorizer_id, $app);
            $card = $official_account->card;

            $cards = [
                'action_name' => 'QR_CARD',
                'expire_seconds' => $wechatCouponBatch->expire_seconds,
                'action_info' => [
                    'card' => [
                        'card_id' => $wechatCouponBatch->card_id,
                        'is_unique_code' => false,
                        'outer_id' => 1,
                    ],
                ],
            ];

            $result = $card->createQrCode($cards);
            abort_if($result['errcode'] > 0, 500, $result['errmsg']);
            $qrcodeUrl = $result['show_qrcode_url'];
        } else {
            $path = 'qrcode/' . $code . '.png';
            $qrcodeApp = QrCode::format('png');
            if ($size) {
                $qrcodeApp->size($size);
            }
            $qrcodeApp->generate($code, $path);
            $qrcodeUrl = env('APP_URL') . '/' . $path;
        }
        cache()->forever($cacheIndex, $qrcodeUrl);
        return $qrcodeUrl;
    }
}

function componentVerify($app)
{
    $component = ComponentVerifyTicket::orderBy('clientdate', 'desc')->first();
    /** @var \EasyWeChat\OpenPlatform\Auth\VerifyTicket $verifyTicket */
    $verifyTicket = $app->verify_ticket;
    $verifyTicket->setTicket($component->ticket);
}

/**
 * @param $authorizer_id
 * @param $app
 * @return \EasyWeChat\OfficialAccount\Application
 */
function getOfficialAccount($authorizer_id, $app)
{
    $authorizer = WeChatAuthorizer::where('id', $authorizer_id)->first();

    abort_if(!$authorizer, 404);

    $officialApp = $app->officialAccount($authorizer->appid);
    $officialApp->access_token->setToken($authorizer->access_token, 7200);
    return $officialApp;
}

/**
 * @param $p_needle
 * @param $p_haystack
 * @return bool
 */
function arrayMultiSearch($needle, $haystack)
{
    foreach ($haystack as $row) {
        if (in_array($needle, $row)) {
            return true;
        }
    }
    return false;
}

if (!function_exists('add_query_string')) {
    function add_query_string($url, $key, $value)
    {
        $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        if (strpos($url, '?') === false) {
            return ($url . '?' . $key . '=' . $value);
        } else {
            return ($url . '&' . $key . '=' . $value);
        }
    }
}

function excelExport(ExportRequest $request)
{
    $type = $request->type;

    $path = config('filesystems')['disks']['qiniu']['url'];
    $export = app($type);

    $fileName = $export->fileName . '_' . time() . '_' . '.' . 'xlsx';
    Excel::store($export, $fileName, 'qiniu');
    return $path . urlencode($fileName);
}
