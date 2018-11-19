<?php

use App\Http\Controllers\Admin\Face\V1\Models\FaceCount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Admin\Face\V1\Models\FaceActivePlayerRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCharacterRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord;
use App\Models\User;
use App\Http\Controllers\Admin\Face\V1\Models\FaceOmoRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FacePhoneRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceActivePlaytimesRecord;
use app\Support\Jenner\Zebra\ArrayGroupBy;
use App\Http\Controllers\Admin\Face\V1\Models\FaceLogRecord;
use App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch;
use EasyWeChat;
use App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer;
use App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket;

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

//task code
function activePlayerClean()
{
    $date = FaceActivePlayerRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $startClientDate = strtotime($date . ' 00:00:00') * 1000;
        $endClientDate = strtotime($date . ' 23:59:59') * 1000;

        $timeArray = [7, 15, 21];

        //按所有人去重 belong='all'
        $sql1 = [];
        for ($i = 0; $i < count($timeArray); $i++) {
            $sql = DB::connection('ar')->table('face_people_time as fpt')
                ->join('avr_official as ao', 'ao.oid', '=', 'fpt.oid')
                ->whereRaw("fpt.clientdate between '$startClientDate' and '$endClientDate' and fpid>0 and playtime>='$timeArray[$i]000'")
                ->selectRaw("fpt.oid as oid");
            if ($date <= '2018-07-01') {
                $sql = $sql->groupBy(DB::raw('fpid*100+fpt.oid'));
            } else {
                $sql = $sql->groupBy(DB::raw('fpid*10000+fpt.oid'));
            }

            $sql1[] = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a" . $timeArray[$i]))
                ->groupBy('oid' . $timeArray[$i])
                ->selectRaw("oid as oid" . $timeArray[$i] . ",count(*) as playernum" . $timeArray[$i]);
        }
        $query = DB::connection('ar')->table(DB::raw("({$sql1[0]->toSql()}) as b"));
        for ($i = 1; $i < count($timeArray); $i++) {
            $query = $query->join(DB::raw("({$sql1[$i]->toSql()}) as b" . $i), function ($join) use ($i, $timeArray) {
                $join->on('b.oid' . $timeArray[0], '=', 'b' . $i . '.oid' . $timeArray[$i]);
            }, null, null, 'left');
        }
        $query->selectRaw("oid" . $timeArray[0] . " as oid");
        for ($i = 0; $i < count($timeArray); $i++) {
            $query->selectRaw("playernum" . $timeArray[$i]);
        }
        $allData = $query->get();

        //按节目去重
        $sql2 = [];
        for ($i = 0; $i < count($timeArray); $i++) {

            $sql = DB::connection('ar')->table('face_people_time as fpt')
                ->join('avr_official as ao', 'ao.oid', '=', 'fpt.oid')
                ->whereRaw("fpt.clientdate between '$startClientDate' and '$endClientDate' and fpid>0 and playtime>='$timeArray[$i]000'")
                ->selectRaw("fpt.oid as oid,belong");
            if ($date <= '2018-07-01') {
                $sql = $sql->groupBy(DB::raw('fpid*100+fpt.oid,belong'));
            } else {
                $sql = $sql->groupBy(DB::raw('fpid*10000+fpt.oid,belong'));
            }
            $sql2[] = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a" . $timeArray[$i]))
                ->groupBy(DB::raw("oid" . $timeArray[$i] . ",belong" . $timeArray[$i]))
                ->selectRaw("oid as oid" . $timeArray[$i] . ",belong as belong" . $timeArray[$i] . ",count(*) as playernum" . $timeArray[$i]);
        }
        $query = DB::connection('ar')->table(DB::raw("({$sql2[0]->toSql()}) as b"));
        for ($i = 1; $i < count($timeArray); $i++) {
            $query = $query->join(DB::raw("({$sql2[$i]->toSql()}) as b" . $i), function ($join) use ($i, $timeArray) {
                $join->on('b.oid' . $timeArray[0], '=', 'b' . $i . '.oid' . $timeArray[$i])
                    ->on('b.belong' . $timeArray[0], '=', 'b' . $i . '.belong' . $timeArray[$i]);
            }, null, null, 'left');
        }
        $query = $query->selectRaw("oid" . $timeArray[0] . " as oid,belong" . $timeArray[0] . " as belong");
        for ($i = 0; $i < count($timeArray); $i++) {
            $query->selectRaw("playernum" . $timeArray[$i]);
        }
        $data = $query->get();


        $count = [];
        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'playernum7' => $item->playernum7,
                'playernum15' => $item->playernum15,
                'playernum21' => $item->playernum21,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'playernum7' => $item->playernum7,
                'playernum15' => $item->playernum15,
                'playernum21' => $item->playernum21,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_active_player')
            ->insert($count);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceActivePlayerRecord::create(['date' => $currentDate]);
}

function activePlayTimesClean()
{
    $date = FaceActivePlaytimesRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $startClientDate = strtotime($date . ' 00:00:00') * 1000;
        $endClientDate = strtotime($date . ' 23:59:59') * 1000;

        $sql = DB::connection('ar')->table("face_collect as fc")
            ->join('avr_official as ao', 'fc.oid', '=', 'ao.oid')
            ->join('face_people_time as fpt', function ($join) {
                $join->on('fc.oid', '=', 'fpt.oid');
                $join->on('fc.belong', '=', 'fpt.belong');
                $join->on('fc.fpid', '=', 'fpt.fpid');
            }, null, null, 'left')
            ->whereRaw("fc.clientdate between '$startClientDate' and '$endClientDate' and fpt.clientdate between '$startClientDate' AND '$endClientDate' and fc.fpid>0")
            ->selectRaw("fc.oid as oid ,fc.belong as belong,fc.fpid as fpid,fc.clientdate as clientdate,playtime");

        $data = $sql->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'fpid' => $item->fpid,
                'clientdate' => $item->clientdate,
                'playtime' => $item->playtime,
            ];
        }
        $group_by_fields = [
            'oid' => function ($value) {
                return $value;
            },
            'belong' => function ($value) {
                return $value;
            },
            'fpid' => function ($value) {
                return $value;
            },

        ];
        $group_by_value = [
            'oid' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['oid'];
                },
                'as' => 'oid'
            ],
            'belong' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['belong'];
                },
                'as' => 'belong'
            ],
            'fpid' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['fpid'];
                },
                'as' => 'fpid'
            ],
            'clientdate' => [
                'callback' => function ($data) {
                    return join(',', array_column($data, 'clientdate'));
                },
                'as' => 'clientdate'
            ],
            'playtime' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['playtime'];
                },
                'as' => 'playtime'
            ],
        ];
        $data = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value);
        $count = [];
        foreach ($data as $item) {
            $clientDates = explode(',', $item['clientdate']);
            sort($clientDates);
            $m = 0;
            $n = 1;
            $num7 = dateRecursion($m, $n, $clientDates, 7000) + 1;
            $num15 = dateRecursion($m, $n, $clientDates, 15000) + 1;
            $num21 = dateRecursion($m, $n, $clientDates, 21000) + 1;

            $count[] = [
                'oid' => $item['oid'],
                'belong' => $item['belong'],
                'fpid' => $item['fpid'],
                'playtimes7' => ($num7 > floor($item['playtime'] / 7000)) ? floor($item['playtime'] / 7000) : $num7,
                'playtimes15' => ($num15 > floor($item['playtime'] / 15000)) ? floor($item['playtime'] / 15000) : $num15,
                'playtimes21' => ($num21 > floor($item['playtime'] / 21000)) ? floor($item['playtime'] / 21000) : $num21,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        $group_by_fields = [
            'oid' => function ($value) {
                return $value;
            },
            'belong' => function ($value) {
                return $value;
            }
        ];
        $group_by_value = [
            'oid' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['oid'];
                },
                'as' => 'oid'
            ],
            'belong' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['belong'];
                },
                'as' => 'belong'
            ],
            'playtimes7' => function ($data) {
                return array_sum(array_column($data, 'playtimes7'));
            },
            'playtimes15' => function ($data) {
                return array_sum(array_column($data, 'playtimes15'));
            },
            'playtimes21' => function ($data) {
                return array_sum(array_column($data, 'playtimes21'));
            },
            'date' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['date'];
                },
                'as' => 'date'
            ],
            'clientdate' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['clientdate'];
                },
                'as' => 'clientdate'
            ]
        ];
        $data = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value);

        $group_by_fields = [
            'oid' => function ($value) {
                return $value;
            }
        ];
        $group_by_value = [
            'oid' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['oid'];
                },
                'as' => 'oid'
            ],
            'belong' => [
                'callback' => function ($value_array) {
                    return 'all';
                },
                'as' => 'belong'
            ],
            'playtimes7' => function ($data) {
                return array_sum(array_column($data, 'playtimes7'));
            },
            'playtimes15' => function ($data) {
                return array_sum(array_column($data, 'playtimes15'));
            },
            'playtimes21' => function ($data) {
                return array_sum(array_column($data, 'playtimes21'));
            },
            'date' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['date'];
                },
                'as' => 'date'
            ],
            'clientdate' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['clientdate'];
                },
                'as' => 'clientdate'
            ]
        ];
        $allData = ArrayGroupBy::groupBy($data, $group_by_fields, $group_by_value);
        DB::connection('ar')->table('xs_face_active_playtimes')->insert(array_merge($data, $allData));
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceActivePlaytimesRecord::create(['date' => $currentDate]);
}

//clientdate递归
function dateRecursion($i, $j, $date, $time)
{
    if ($j < count($date)) {
        if ($date[$j] - $date[$i] >= $time) {
            $i = $j;
            $j++;
            return 1 + dateRecursion($i, $j, $date, $time);
        }
        $j++;
        return 0 + dateRecursion($i, $j, $date, $time);
    }
    return 0;
}

function omoClean()
{
    $date = FaceOmoRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $sql = DB::table('short_url_records')
            ->whereRaw("date_format(created_at,'%Y-%m-%d')='$date' and face_id <> '' and utm_source <> '' ")
            ->groupBy(DB::raw(" oid, belong, utm_term "))
            ->selectRaw("utm_source as oid,utm_campaign as belong,utm_term,count(*) as num");

        $scanAndShareSql = DB::table(DB::raw("({$sql->toSql()}) as a"))
            ->groupBy(DB::raw("oid,belong"))
            ->selectRaw("oid,belong,sum(if(utm_term = '', num, 0)) as omo_scannum,sum(if(utm_term = 'wechat_share', num, 0)) as omo_sharenum");

        $sql1 = DB::table('short_url_records')
            ->whereRaw(" date_format(created_at,'%Y-%m-%d')='$date' and face_id <> '' and utm_source <> '' ")
            ->groupBy(DB::raw(" oid, belong, face_id "))
            ->selectRaw("utm_source as oid,utm_campaign as belong,face_id");

        $outSql = DB::table(DB::raw("({$sql1->toSql()}) as b"))
            ->groupBy(DB::raw("oid,belong"))
            ->selectRaw("oid,belong,count(*) as omo_outnum");

        $data = DB::table(DB::raw("({$scanAndShareSql->toSql()}) as a1"))
            ->join(DB::raw("({$outSql->toSql()}) as b1"), function ($join) {
                $join->on('a1.oid', '=', 'b1.oid');
                $join->on('a1.belong', '=', 'b1.belong');
            })
            ->selectRaw("a1.oid as oid,a1.belong as belong ,b1.omo_outnum as omo_outnum,a1.omo_scannum as omo_scannum ,a1.omo_sharenum as omo_sharenum")
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'omo_outnum' => $item->omo_outnum,
                'omo_scannum' => $item->omo_scannum,
                'omo_sharenum' => $item->omo_sharenum,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_omo')->insert($count);

        $clientDate = strtotime($date) * 1000;
        $allData = DB::connection('ar')->table('xs_face_omo')
            ->whereRaw("clientdate='$clientDate'")
            ->groupBy('oid')
            ->selectRaw(" oid,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(omo_sharenum) as omo_sharenum,date,clientdate")
            ->get();
        $count = [];
        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'omo_outnum' => $item->omo_outnum,
                'omo_scannum' => $item->omo_scannum,
                'omo_sharenum' => $item->omo_sharenum,
                'date' => $item->date,
                'clientdate' => $item->clientdate,
            ];
        }
        DB::connection('ar')->table('xs_face_omo')->insert($count);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceOmoRecord::create(['date' => $currentDate]);
}

function phoneClean()
{
    $date = FacePhoneRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $startClientDate = strtotime($date . ' 00:00:00') * 1000;
        $endClientDate = strtotime($date . ' 23:59:59') * 1000;

        //belong='all'
        $sql = DB::connection('ar')->table('face_ad_log as fal')
            ->join('avr_official as ao', 'fal.oid', '=', 'ao.oid')
            ->whereRaw("fal.clientdate between '$startClientDate' and '$endClientDate' and fpid <> 0")
            ->groupBy(DB::raw("oid,unionid"))
            ->selectRaw("fal.oid as oid,unionid");

        $allData = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->selectRaw("oid,count(length(unionid)=11 or null) as phonenum,count(length(unionid)<>11 or null) as oanum")
            ->groupBy("oid")
            ->get();

        //按节目去重
        $sql1 = DB::connection('ar')->table('face_ad_log as fal')
            ->join('avr_official as ao', 'fal.oid', '=', 'ao.oid')
            ->whereRaw("fal.clientdate between '$startClientDate' and '$endClientDate' and fpid <> 0")
            ->groupBy(DB::raw("oid,belong,unionid"))
            ->selectRaw("fal.oid as oid,belong,unionid");

        $data = DB::connection('ar')->table(DB::raw("({$sql1->toSql()}) as a"))
            ->selectRaw("oid,belong,count(length(unionid)=11 or null) as phonenum,count(length(unionid)<>11 or null) as oanum")
            ->groupBy(DB::raw("oid,belong"))
            ->get();
        $count = [];
        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'phonenum' => $item->phonenum,
                'oanum' => $item->oanum,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'phonenum' => $item->phonenum,
                'oanum' => $item->oanum,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_phone')
            ->insert($count);


        //不去重
        $times = DB::connection('ar')->table('face_ad_log as fal')
            ->join('avr_official as ao', 'fal.oid', '=', 'ao.oid')
            ->whereRaw("fal.clientdate between '$startClientDate' and '$endClientDate'")
            ->groupBy(DB::raw("fal.oid,belong"))
            ->selectRaw("fal.oid as oid,belong,count(length(unionid)=11 or null) as phonetimes,count(length(unionid)<>11 or null) as oatimes")
            ->get();
        $allTimes = DB::connection('ar')->table('face_ad_log as fal')
            ->join('avr_official as ao', 'fal.oid', '=', 'ao.oid')
            ->whereRaw("fal.clientdate between '$startClientDate' and '$endClientDate'")
            ->groupBy('fal.oid')
            ->selectRaw("fal.oid as oid,count(length(unionid)=11 or null) as phonetimes,count(length(unionid)<>11 or null) as oatimes")
            ->get();
        $count = [];
        foreach ($times as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'phonetimes' => $item->phonetimes,
                'oatimes' => $item->oatimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        foreach ($allTimes as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'phonetimes' => $item->phonetimes,
                'oatimes' => $item->oatimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_phone_times')->insert($count);

        $date = (new Carbon($date))->addDay(1)->toDateString();

    }
    FacePhoneRecord::create(['date' => $currentDate]);
}

function mergeActiveOmoLook()
{
    $date = FaceCountRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $sql1 = DB::connection('ar')->table('face_count_log as fcl')
            ->whereRaw("date_format(fcl.date,'%Y-%m-%d')='$date'")
            ->selectRaw("fcl.oid,fcl.belong,looknum,playernum,outnum,scannum,lovenum");

        $clientDate = strtotime($date . ' 00:00:00') * 1000;
        $sql2 = DB::connection('ar')->table('xs_face_active_player')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,playernum7,playernum15,playernum21");

        $sql3 = DB::connection('ar')->table('xs_face_omo')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,omo_outnum,omo_scannum,omo_sharenum");

        $sql4 = DB::connection('ar')->table('xs_face_phone')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,phonenum,oanum");
        $sql5 = DB::connection('ar')->table('xs_face_phone_times')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,phonetimes,oatimes");
        $sql6 = DB::connection('ar')->table('xs_face_active_playtimes')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,playtimes7,playtimes15,playtimes21");

        $data = DB::connection('ar')->table(DB::raw("({$sql1->toSql()}) as a"))
            ->join(DB::raw("({$sql2->toSql()}) as b"), function ($join) {
                $join->on('a.oid', '=', 'b.oid');
                $join->on('a.belong', '=', 'b.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql3->toSql()}) as c"), function ($join) {
                $join->on('a.oid', '=', 'c.oid');
                $join->on('a.belong', '=', 'c.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql4->toSql()}) as d"), function ($join) {
                $join->on('a.oid', '=', 'd.oid');
                $join->on('a.belong', '=', 'd.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql5->toSql()}) as e"), function ($join) {
                $join->on('a.oid', '=', 'e.oid');
                $join->on('a.belong', '=', 'e.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql6->toSql()}) as f"), function ($join) {
                $join->on('a.oid', '=', 'f.oid');
                $join->on('a.belong', '=', 'f.belong');
            }, null, null, 'left')
            ->selectRaw("a.oid as oid,a.belong as belong,looknum,playernum7,playernum15,playernum21,playernum,outnum,scannum,omo_outnum,omo_scannum,omo_sharenum,lovenum,phonenum,oanum,phonetimes,oatimes,playtimes7,playtimes15,playtimes21")
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'looknum' => $item->looknum,
                'playernum7' => $item->playernum7 ? $item->playernum7 : 0,
                'playernum15' => $item->playernum15 ? $item->playernum15 : 0,
                'playernum21' => $item->playernum21 ? $item->playernum21 : 0,
                'playernum' => $item->playernum,
                'outnum' => $item->outnum,
                'scannum' => $item->scannum,
                'omo_outnum' => $item->omo_outnum ? $item->omo_outnum : 0,
                'omo_scannum' => $item->omo_scannum ? $item->omo_scannum : 0,
                'omo_sharenum' => $item->omo_sharenum ? $item->omo_sharenum : 0,
                'lovenum' => $item->lovenum,
                'phonenum' => $item->phonenum,
                'oanum' => $item->oanum,
                'phonetimes' => $item->phonetimes,
                'oatimes' => $item->oatimes,
                'playtimes7' => $item->playtimes7,
                'playtimes15' => $item->playtimes15,
                'playtimes21' => $item->playtimes21,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_count_log')->insert($count);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceCountRecord::create(['date' => $currentDate]);
}

function mauClean()
{
    $date = FaceMauRecord::query()->max('date');
    $currentDate = Carbon::now()->toDateString();
    while ((new Carbon($date))->format('Y-m') < (new Carbon($currentDate))->format('Y-m')) {
        $startDate = $date;
        $endDate = (new Carbon($date))->endOfMonth()->toDateString();
        $startClientDate = strtotime($startDate . ' 00:00:00') * 1000;
        $endClientDate = strtotime($endDate . ' 23:59:59') * 1000;

        $sql = DB::connection('ar')->table('face_people_time as fpt')
            ->join('avr_official as ao', 'fpt.oid', '=', 'ao.oid')
            ->whereRaw("fpt.clientdate between '$startClientDate' and '$endClientDate' and playtime >= 7000 and fpt.oid not in(16, 19, 30, 31, 177, 182, 327, 328, 329, 334, 335)")
            ->groupBy(DB::raw('fpid * 10000 + fpt.oid'))
            ->selectRaw(" fpid ");
        $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->selectRaw("count(*) as playernum")
            ->first();
        $count = [
            'active_player' => $data->playernum,
            'date' => $date
        ];
        DB::connection('ar')->table('xs_face_mau')
            ->insert($count);
        $date = (new Carbon($date))->addMonth(1)->toDateString();
    }
}

function mauCleanByMarket()
{
    $date = FaceMauRecord::query()->max('date');
    $currentDate = Carbon::now()->toDateString();
    while ((new Carbon($date))->format('Y-m') < (new Carbon($currentDate))->format('Y-m')) {
        $startDate = $date;
        $endDate = (new Carbon($date))->endOfMonth()->toDateString();
        $startClientDate = strtotime($startDate . ' 00:00:00') * 1000;
        $endClientDate = strtotime($endDate . ' 23:59:59') * 1000;

        $sql = DB::connection('ar')->table('face_people_time as fpt')
            ->join('avr_official as ao', 'fpt.oid', '=', 'ao.oid')
            ->whereRaw("fpt . clientdate between '$startClientDate' and '$endClientDate' and playtime >= 7000 and fpt . oid not in(16, 19, 30, 31, 177, 182, 327, 328, 329, 334, 335)")
            ->groupBy(DB::raw('ao.marketid,fpid * 10000 + fpt.oid'))
            ->selectRaw("marketid,fpid");
        $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->selectRaw("marketid,count(*) as playernum")
            ->groupBy('marketid')
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'active_player' => $item->playernum,
                'marketid' => $item->marketid,
                'date' => $date
            ];
        }
        DB::connection('ar')->table('xs_face_mau_market')
            ->insert($count);
        $date = (new Carbon($date))->addMonth(1)->toDateString();
    }
    FaceMauRecord::create(['date' => $date]);
}

function faceCharacterClean()
{
    $date = FaceCharacterRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();

    while ($date < $currentDate) {
        $startDate = strtotime($date . " 00:00:00") * 1000;
        $endDate = strtotime($date . " 23:59:59") * 1000;

        if ($date < '2018-01-01') {
            $century10 = "when age<=7 then '10' ";
            $century00 = "when age > 7 and age <= 17 then '00' ";
            $century90 = "when age > 17 and age <= 27 then '90' ";
            $century80 = "when age > 17 and age <= 37 then '80' ";
            $century70 = "when age > 37 then '70' ";
            $century = $century10 . $century00 . $century90 . $century80 . $century70;
        } else {
            $age1 = (new Carbon($currentDate))->diffInYears('2010-01-01');
            $age2 = (new Carbon($currentDate))->diffInYears('2000-01-01');
            $age3 = (new Carbon($currentDate))->diffInYears('1990-01-01');
            $age4 = (new Carbon($currentDate))->diffInYears('1980-01-01');
            $century10 = "when age<='$age1' then '10' ";
            $century00 = "when age > '$age1' and age <= '$age2' then '00' ";
            $century90 = "when age > '$age2' and age <= '$age3' then '90' ";
            $century80 = "when age > '$age3' and age <= '$age4' then '80' ";
            $century70 = "when age > '$age4' then '70' ";
            $century = $century10 . $century00 . $century90 . $century80 . $century70;
        }


        $sql = DB::connection('ar')->table('face_collect as fc')
            ->join('avr_official as ao', 'ao.oid', '=', 'fc.oid')
            ->selectRaw("date_format(concat(date(fc.date), ' ', hour(fc.date), ':', floor(minute(fc.date) / 30) * 30), '%Y-%m-%d %H:%i') as time,case " . $century . "else 0 end as century,gender,fc.oid as oid,belong")
            ->whereRaw("fc.clientdate between '$startDate' and '$endDate' and fpid > 0 and fc.type = 'play' ")
            ->orderBy('isold', 'desc');

        //按所有人去重 belong='all'
        if ($date <= '2018-07-01') {
            $sql1 = $sql->groupBy(DB::raw('fpid*100+fc.oid'));
        } else {
            $sql1 = $sql->groupBy(DB::raw('fpid*10000+fc.oid'));
        }
        $allData = DB::connection('ar')->table(DB::raw("({$sql1->toSql()}) as a"))
            ->groupBy(DB::raw("oid,time,century,gender"))
            ->orderBy(DB::raw("oid,time,century,gender"))
            ->selectRaw("oid,time,century,gender,count(*) as looknum")
            ->get();

        //按节目去重
        if ($date <= '2018-07-01') {
            $sql2 = $sql->groupBy(DB::raw('fpid*100+fc.oid,belong'));
        } else {
            $sql2 = $sql->groupBy(DB::raw('fpid*10000+fc.oid,belong'));
        }
        $data = DB::connection('ar')->table(DB::raw("({$sql2->toSql()}) as a"))
            ->groupBy(DB::raw("oid,belong,time,century,gender"))
            ->orderBy(DB::raw("oid,belong,time,century,gender"))
            ->selectRaw("oid,belong,time,century,gender,count(*) as looknum")
            ->get();

        $count = [];
        foreach ($allData as $item) {
            $item = json_decode(json_encode($item), true);
            $item['belong'] = 'all';
            $item['time'] = getDateFormat((new Carbon($item['time']))->addMinutes(30)->format('H:i'));
            $item['date'] = $date;
            $item['clientdate'] = strtotime($date) * 1000;
            $count[] = $item;
        }
        foreach ($data as $item) {
            $item = json_decode(json_encode($item), true);
            $item['time'] = getDateFormat((new Carbon($item['time']))->addMinutes(30)->format('H:i'));
            $item['date'] = $date;
            $item['clientdate'] = strtotime($date) * 1000;
            $count[] = $item;
        }


        $group_by_fields = [
            'oid' => function ($value) {
                return $value;
            },
            'belong' => function ($value) {
                return $value;
            },
            'time' => function ($value) {
                return $value;
            }
        ];
        $group_by_value = groupByValueCharacter('looknum');
        $characterData = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value);

        DB::connection('ar')->table('xs_face_character_count')->insert($characterData);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }

    FaceCharacterRecord::create(['date' => $currentDate]);
}

function getDateFormat($date)
{
    $date = (new Carbon($date))->format('H:i');
    if ($date > '00:00' && $date <= '10:00') {
        return '10:00';
    } else if ($date > '10:00' && $date <= '12:00') {
        return '12:00';
    } else if ($date > '12:00' && $date <= '14:00') {
        return '14:00';
    } else if ($date > '14:00' && $date <= '16:00') {
        return '16:00';
    } else if ($date > '16:00' && $date <= '18:00') {
        return '18:00';
    } else if ($date > '18:00' && $date <= '20:00') {
        return '20:00';
    } else if ($date > '20:00' && $date <= '22:00') {
        return '22:00';
    } else {
        return '24:00';
    }
}


function groupByValueCharacter($x)
{
    return $data = [
        'oid' => [
            'callback' => function ($value_array) {
                return $value_array[0]['oid'];
            },
            'as' => 'oid'
        ],
        'belong' => [
            'callback' => function ($value_array) {
                return $value_array[0]['belong'];
            },
            'as' => 'belong'
        ],
        'time' => [
            'callback' => function ($value_array) {
                return $value_array[0]['time'];
            },
            'as' => 'time'
        ],
        'century00_bnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['century'] == '00';
            }), $x));
        },
        'century00_gnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['century'] == '00';
            }), $x));
        },
        'century90_bnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['century'] == '90';
            }), $x));
        },
        'century90_gnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['century'] == '90';
            }), $x));
        },
        'century80_bnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['century'] == '80';
            }), $x));
        },
        'century80_gnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['century'] == '80';
            }), $x));
        },
        'century70_bnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['century'] == '70';
            }), $x));
        },
        'century70_gnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['century'] == '70';
            }), $x));
        },
        'century10_bnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['century'] == '10';
            }), $x));
        },
        'century10_gnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['century'] == '10';
            }), $x));
        },
        'date' => [
            'callback' => function ($value_array) {
                return $value_array[0]['date'];
            },
            'as' => 'date'
        ],
        'clientdate' => [
            'callback' => function ($value_array) {
                return $value_array[0]['clientdate'];
            },
            'as' => 'clientdate'
        ]
    ];

}

function faceLogClean()
{
    $date = FaceLogRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {

        $data = DB::connection('ar')->table('face_log')
            ->whereRaw("date_format(date,'%Y-%m-%d')='$date' and type='looker'")
            ->selectRaw("oid,belong,bnum,gnum,age10b,age10g,age18b,age18g,age30b,age30g,age40b,age40g,age60b,age60g,age61b,age61g")
            ->get();

        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'bnum' => $item->bnum,
                'gnum' => $item->gnum,
                'age10b' => $item->age10b,
                'age10g' => $item->age10g,
                'age18b' => $item->age18b,
                'age18g' => $item->age18g,
                'age30b' => $item->age30b,
                'age30g' => $item->age30g,
                'age40b' => $item->age40b,
                'age40g' => $item->age40g,
                'age60b' => $item->age60b,
                'age60g' => $item->age60g,
                'age61b' => $item->age61b,
                'age61g' => $item->age61g,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_log')->insert($count);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceLogRecord::create(['date' => $currentDate]);
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

    return $app->officialAccount($authorizer->appid, $authorizer->refresh_token);
}