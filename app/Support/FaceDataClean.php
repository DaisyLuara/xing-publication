<?php

use App\Http\Controllers\Admin\Face\V1\Models\FaceActivePlayerRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceActivePlaytimesRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCharacterRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCharacterTimesRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCouponRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceLogRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceLogTimesRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceLookTimesRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceOmoRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FacePermeabilityRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FacePhoneRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FacePlayCharacterRecord;
use App\Http\Controllers\Admin\Face\V1\Models\FaceVerifyRecord;
use App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember;
use App\Http\Controllers\Admin\Team\V1\Models\TeamPersonReward;
use app\Support\Jenner\Zebra\ArrayGroupBy;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * 围观人次清洗
 */
function lookTimesClean()
{
    $date = FaceLookTimesRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $startClientDate = strtotime($date . ' 00:00:00') * 1000;
        $endClientDate = strtotime($date . ' 23:59:59') * 1000;

        $data = DB::connection('ar')->table('face_collect as fc')
            ->join('avr_official as ao', 'ao.oid', '=', 'fc.oid')
            ->selectRaw("fc.oid as oid,belong,count(*) as looktimes")
            ->whereRaw("fc.clientdate between '$startClientDate' and '$endClientDate' and fpid > 0 and fc.type = 'play' ")
            ->groupBy(DB::raw("fc.oid,belong"))
            ->get();

        $allData = DB::connection('ar')->table('face_collect as fc')
            ->join('avr_official as ao', 'ao.oid', '=', 'fc.oid')
            ->selectRaw("fc.oid as oid,belong,count(*) as looktimes")
            ->whereRaw("fc.clientdate between '$startClientDate' and '$endClientDate' and fpid > 0 and fc.type = 'play' ")
            ->groupBy(DB::raw("fc.oid"))
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'looktimes' => $item->looktimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'looktimes' => $item->looktimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        DB::connection('ar')->table('xs_face_look_times')->insert($count);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceLookTimesRecord::create(['date' => $currentDate]);
}

/**
 * 活跃玩家清洗
 */
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

/**
 * 活跃玩家人次清洗
 */
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

/**
 * omo清洗
 */
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

/**
 * 手机号，公众号清洗
 */
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

/** 优惠券清洗 */
function couponTimesClean()
{
    $date = FaceCouponRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $data = DB::table('coupons')
            ->selectRaw("oid,belong,count(*) as coupontimes")
            ->whereRaw("date_format(created_at,'%Y-%m-%d')='$date' and oid>0 ")
            ->groupBy(DB::raw("oid,belong"))
            ->get();

        $allData = DB::table('coupons')
            ->selectRaw("oid,belong,count(*) as coupontimes")
            ->whereRaw("date_format(created_at,'%Y-%m-%d')='$date' and oid>0 ")
            ->groupBy('oid')
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'coupontimes' => $item->coupontimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'coupontimes' => $item->coupontimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        DB::connection('ar')->table('xs_face_coupon_times')->insert($count);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceCouponRecord::create(['date' => $currentDate]);
}

/**
 * 核销清洗
 */
function verifyTimesClean()
{
    $date = FaceVerifyRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $data = DB::table('coupons')
            ->selectRaw("oid,belong,count(*) as verifytimes")
            ->whereRaw("date_format(updated_at,'%Y-%m-%d')='$date' and status=1 and oid>0 ")
            ->groupBy(DB::raw("oid,belong"))
            ->get();

        $allData = DB::table('coupons')
            ->selectRaw("oid,belong,count(*) as verifytimes")
            ->whereRaw("date_format(updated_at,'%Y-%m-%d')='$date' and status=1 and oid>0 ")
            ->groupBy('oid')
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'verifytimes' => $item->verifytimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'verifytimes' => $item->verifytimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        DB::connection('ar')->table('xs_face_verify_times')->insert($count);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceVerifyRecord::create(['date' => $currentDate]);
}


/**
 * 数据聚合
 */
function mergeActiveOmoLook()
{
    $date = FaceCountRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $sql1 = DB::connection('ar')->table('face_count_log as fcl')
            ->whereRaw("date_format(fcl.date,'%Y-%m-%d')='$date'")
            ->selectRaw("fcl.oid,fcl.belong,looknum,playernum,outnum,scannum,lovenum")
            ->groupBy(DB::raw("fcl.oid,fcl.belong"));

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

        $sql7 = DB::connection('ar')->table('xs_face_verify_times')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,verifytimes");

        $sql8 = DB::connection('ar')->table('xs_face_coupon_times')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,coupontimes");

        $sql = DB::connection('ar')->table('xs_face_look_times')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,looktimes");

        $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->join(DB::raw("({$sql1->toSql()}) as b"), function ($join) {
                $join->on('a.oid', '=', 'b.oid');
                $join->on('a.belong', '=', 'b.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql2->toSql()}) as c"), function ($join) {
                $join->on('a.oid', '=', 'c.oid');
                $join->on('a.belong', '=', 'c.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql3->toSql()}) as d"), function ($join) {
                $join->on('a.oid', '=', 'd.oid');
                $join->on('a.belong', '=', 'd.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql4->toSql()}) as e"), function ($join) {
                $join->on('a.oid', '=', 'e.oid');
                $join->on('a.belong', '=', 'e.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql5->toSql()}) as f"), function ($join) {
                $join->on('a.oid', '=', 'f.oid');
                $join->on('a.belong', '=', 'f.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql6->toSql()}) as g"), function ($join) {
                $join->on('a.oid', '=', 'g.oid');
                $join->on('a.belong', '=', 'g.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql7->toSql()}) as h"), function ($join) {
                $join->on('a.oid', '=', 'h.oid');
                $join->on('a.belong', '=', 'h.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql8->toSql()}) as i"), function ($join) {
                $join->on('a.oid', '=', 'i.oid');
                $join->on('a.belong', '=', 'i.belong');
            }, null, null, 'left')
            ->selectRaw("a.oid as oid,a.belong as belong,looknum,playernum7,playernum15,playernum21,playernum,outnum,scannum,omo_outnum,omo_scannum,omo_sharenum,lovenum,phonenum,oanum,phonetimes,oatimes,playtimes7,playtimes15,playtimes21,looktimes,verifytimes,coupontimes")
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
                'looktimes' => $item->looktimes,
                'lovetimes' => $item->oatimes + $item->phonetimes,
                'verifytimes' => $item->verifytimes,
                'coupontimes' => $item->coupontimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_count_log')->insert($count);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceCountRecord::create(['date' => $currentDate]);
}


/**
 * 月活清洗
 */
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

/**
 * 月活按商场清洗
 */
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


/**
 * 围观人群特征清洗
 */
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
        $group_by_value = groupByValueCharacter('looknum', false);
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

/**
 * 围观人群渗透率清洗
 */
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
 * 围观人次人群特征清洗
 */
function faceCharacterTimesClean()
{
    $date = FaceCharacterTimesRecord::query()->max('date');
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

        $time1 = "when date_format(fc.date, '%H:%i') >= '00:00' and date_format(fc.date, '%H:%i') < '10:00' then '10:00' ";
        $time2 = "when date_format(fc.date, '%H:%i') >= '10:00' and date_format(fc.date, '%H:%i') < '12:00' then '12:00' ";
        $time3 = "when date_format(fc.date, '%H:%i') >= '12:00' and date_format(fc.date, '%H:%i') < '14:00' then '14:00' ";
        $time4 = "when date_format(fc.date, '%H:%i') >= '14:00' and date_format(fc.date, '%H:%i') < '16:00' then '16:00' ";
        $time5 = "when date_format(fc.date, '%H:%i') >= '16:00' and date_format(fc.date, '%H:%i') < '18:00' then '18:00' ";
        $time6 = "when date_format(fc.date, '%H:%i') >= '18:00' and date_format(fc.date, '%H:%i') < '20:00' then '20:00' ";
        $time7 = "when date_format(fc.date, '%H:%i') >= '20:00' and date_format(fc.date, '%H:%i') < '22:00' then '22:00' ";
        $time = $time1 . $time2 . $time3 . $time4 . $time5 . $time6 . $time7;


        $data = DB::connection('ar')->table('face_collect as fc')
            ->join('avr_official as ao', 'ao.oid', '=', 'fc.oid')
            ->selectRaw("case " . $time . "else'24:00' end as time,case " . $century . "else 0 end as century,gender,fc.oid as oid,belong,count(*) as looktimes")
            ->whereRaw("fc.clientdate between '$startDate' and '$endDate' and fpid > 0 and fc.type = 'play' ")
            ->groupBy(DB::raw("oid,belong,time,century,gender"))
            ->orderBy('isold', 'desc')
            ->get();


        $allData = DB::connection('ar')->table('face_collect as fc')
            ->join('avr_official as ao', 'ao.oid', '=', 'fc.oid')
            ->selectRaw("case " . $time . "else'24:00' end as time,case " . $century . "else 0 end as century,gender,fc.oid as oid,belong,count(*) as looktimes")
            ->whereRaw("fc.clientdate between '$startDate' and '$endDate' and fpid > 0 and fc.type = 'play' ")
            ->groupBy(DB::raw("oid,time,century,gender"))
            ->orderBy('isold', 'desc')
            ->get();

        $count = [];
        foreach ($data as $item) {
            $item = json_decode(json_encode($item), true);
            $item['date'] = $date;
            $item['clientdate'] = strtotime($date) * 1000;
            $count[] = $item;
        }

        foreach ($allData as $item) {
            $item = json_decode(json_encode($item), true);
            $item['belong'] = 'all';
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
        $group_by_value = groupByValueCharacter('looktimes', false);
        $characterTimesData = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value);

        DB::connection('ar')->table('xs_face_character_count_times')->insert($characterTimesData);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceCharacterTimesRecord::create(['date' => $currentDate]);
}

/**
 * 围观人次渗透率清洗
 */
function faceLogTimesClean()
{
    $date = FaceLogTimesRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();

    while ($date < $currentDate) {
        $startDate = strtotime($date . " 00:00:00") * 1000;
        $endDate = strtotime($date . " 23:59:59") * 1000;

        $age1 = "when age<=10 then '10' ";
        $age2 = "when age > 10 and age <= 18 then '18' ";
        $age3 = "when age > 18 and age <= '30' then '30' ";
        $age4 = "when age > 30 and age <= '40' then '40' ";
        $age5 = "when age > 40 and age <= '60' then '60' ";
        $age = $age1 . $age2 . $age3 . $age4 . $age5;


        $data = DB::connection('ar')->table('face_collect as fc')
            ->join('avr_official as ao', 'ao.oid', '=', 'fc.oid')
            ->selectRaw("case " . $age . "else '61' end as age,fc.oid as oid,belong,gender,count(*) as looktimes")
            ->whereRaw("fc.clientdate between '$startDate' and '$endDate' and fpid > 0 and fc.type = 'play' ")
            ->groupBy(DB::raw("fc.oid,belong,gender,age"))
            ->get();

        $allData = DB::connection('ar')->table('face_collect as fc')
            ->join('avr_official as ao', 'ao.oid', '=', 'fc.oid')
            ->selectRaw("case " . $age . "else '61' end as age,fc.oid as oid,belong,gender,count(*) as looktimes")
            ->whereRaw("fc.clientdate between '$startDate' and '$endDate' and fpid > 0 and fc.type = 'play' ")
            ->groupBy(DB::raw("fc.oid,gender,age"))
            ->get();

        $count = [];
        foreach ($data as $item) {
            $item = json_decode(json_encode($item), true);
            $item['date'] = $date;
            $item['clientdate'] = strtotime($date) * 1000;
            $count[] = $item;
        }

        foreach ($allData as $item) {
            $item = json_decode(json_encode($item), true);
            $item['belong'] = 'all';
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
            }
        ];

        $group_by_value = groupByValuePermeability('looktimes', false);
        $faceLogTimesData = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value);

        DB::connection('ar')->table('xs_face_log_times')->insert($faceLogTimesData);
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceLogTimesRecord::create(['date' => $currentDate]);
}

/**
 * 7s,15s,21s人群特征清洗
 */
function playTimesCharacterClean()
{
    $date = FacePlayCharacterRecord::query()->max('date');
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
            ->selectRaw("fc.oid as oid ,fc.belong as belong,fc.gender as gender,fc.age as age,fc.fpid as fpid,fc.date as date,fc.clientdate as clientdate,playtime");
        $data = $sql->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'fpid' => $item->fpid,
                'gender' => $item->gender,
                'age' => $item->age,
                'date' => $item->date,
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
            'gender' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['gender'];
                },
                'as' => 'gender'
            ],
            'age' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['age'];
                },
                'as' => 'age'
            ],
            'date' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['date'];
                },
                'as' => 'date'
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
        $data = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value);//concat clientdate

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
                'gender' => $item['gender'],
                'century' => getAgeFormat($item['age'], $date),
                'time' => getDateFormatCharacter($item['date']),
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
            },
            'time' => function ($value) {
                return $value;
            }
        ];
        $group_by_value_character7 = groupByValueCharacter('playtimes7', false);
        $group_by_value_character15 = groupByValueCharacter('playtimes15', false);
        $group_by_value_character21 = groupByValueCharacter('playtimes21', false);
        $data7 = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value_character7);
        $data15 = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value_character15);
        $data21 = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value_character21);

        $group_by_fields_all = [
            'oid' => function ($value) {
                return $value;
            },
            'time' => function ($value) {
                return $value;
            }
        ];
        $group_by_value_character7_all = groupByValueCharacter('playtimes7', true);
        $group_by_value_character15_all = groupByValueCharacter('playtimes15', true);
        $group_by_value_character21_all = groupByValueCharacter('playtimes21', true);
        $data7_all = ArrayGroupBy::groupBy($count, $group_by_fields_all, $group_by_value_character7_all);
        $data15_all = ArrayGroupBy::groupBy($count, $group_by_fields_all, $group_by_value_character15_all);
        $data21_all = ArrayGroupBy::groupBy($count, $group_by_fields_all, $group_by_value_character21_all);

        DB::connection('ar')->table('xs_face_playtimes7_character_count')->insert(array_merge($data7, $data7_all));
        DB::connection('ar')->table('xs_face_playtimes15_character_count')->insert(array_merge($data15, $data15_all));
        DB::connection('ar')->table('xs_face_playtimes21_character_count')->insert(array_merge($data21, $data21_all));
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FacePlayCharacterRecord::create(['date' => $currentDate]);
}

/**
 *7s,15s,21s人次用户渗透率
 */
function playTimesPermeabilityClean()
{
    $date = FacePermeabilityRecord::query()->max('date');
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
            ->selectRaw("fc.oid as oid ,fc.belong as belong,fc.gender as gender,fc.age as age,fc.fpid as fpid,fc.clientdate as clientdate,playtime");
        $data = $sql->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'fpid' => $item->fpid,
                'gender' => $item->gender,
                'age' => $item->age,
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
            'gender' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['gender'];
                },
                'as' => 'gender'
            ],
            'age' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['age'];
                },
                'as' => 'age'
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
                'gender' => $item['gender'],
                'age' => $item['age'],
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
        $group_by_value_playtimes7 = groupByValuePermeability('playtimes7', false);
        $group_by_value_playtimes15 = groupByValuePermeability('playtimes15', false);
        $group_by_value_playtimes21 = groupByValuePermeability('playtimes21', false);
        $data7 = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value_playtimes7);
        $data15 = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value_playtimes15);
        $data21 = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value_playtimes21);

        $group_by_fields_all = [
            'oid' => function ($value) {
                return $value;
            }
        ];
        $group_by_value_playtimes7_all = groupByValuePermeability('playtimes7', true);
        $group_by_value_playtimes15_all = groupByValuePermeability('playtimes15', true);
        $group_by_value_playtimes21_all = groupByValuePermeability('playtimes21', true);
        $data7_all = ArrayGroupBy::groupBy($count, $group_by_fields_all, $group_by_value_playtimes7_all);
        $data15_all = ArrayGroupBy::groupBy($count, $group_by_fields_all, $group_by_value_playtimes15_all);
        $data21_all = ArrayGroupBy::groupBy($count, $group_by_fields_all, $group_by_value_playtimes21_all);
        DB::connection('ar')->table('xs_face_playtimes7_permeability')->insert(array_merge($data7, $data7_all));
        DB::connection('ar')->table('xs_face_playtimes15_permeability')->insert(array_merge($data15, $data15_all));
        DB::connection('ar')->table('xs_face_playtimes21_permeability')->insert(array_merge($data21, $data21_all));
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }

    FacePermeabilityRecord::create(['date' => $currentDate]);
}

function groupByValueCharacter($x, $all)
{
    return $data = [
        'oid' => [
            'callback' => function ($value_array) {
                return $value_array[0]['oid'];
            },
            'as' => 'oid'
        ],
        'belong' => [
            'callback' => function ($value_array) use ($all) {
                if ($all) {
                    return 'all';
                } else {
                    return $value_array[0]['belong'];
                }
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

function groupByValuePermeability($x, $all)
{
    return $data = [
        'oid' => [
            'callback' => function ($value_array) {
                return $value_array[0]['oid'];
            },
            'as' => 'oid'
        ],
        'belong' => [
            'callback' => function ($value_array) use ($all) {
                if ($all) {
                    return 'all';
                } else {
                    return $value_array[0]['belong'];
                }
            },
            'as' => 'belong'
        ],
        'bnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male';
            }), $x));
        },
        'gnum' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female';
            }), $x));
        },
        'age10b' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['age'] <= 10;
            }), $x));
        },
        'age10g' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['age'] <= 10;
            }), $x));
        },
        'age18b' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['age'] > 10 and $arr['age'] <= 18;
            }), $x));
        },
        'age18g' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['age'] > 10 and $arr['age'] <= 18;
            }), $x));
        },
        'age30b' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['age'] > 18 and $arr['age'] <= 30;
            }), $x));
        },
        'age30g' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['age'] > 18 and $arr['age'] <= 30;
            }), $x));
        },
        'age40b' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['age'] > 30 and $arr['age'] <= 40;
            }), $x));
        },
        'age40g' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['age'] > 30 and $arr['age'] <= 40;
            }), $x));
        },
        'age60b' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['age'] > 40 and $arr['age'] <= 60;
            }), $x));
        },
        'age60g' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['age'] > 40 and $arr['age'] <= 60;
            }), $x));
        },
        'age61b' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Male' and $arr['age'] > 60;
            }), $x));
        },
        'age61g' => function ($data) use ($x) {
            return array_sum(array_column(array_filter($data, function ($arr) {
                return $arr['gender'] == 'Female' and $arr['age'] > 60;
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

function getAgeFormat($age, $date)
{
    if ($date < '2018-01-01') {
        if ($age <= 7) {
            return '10';
        } else if ($age > 7 && $age <= 17) {
            return '00';
        } else if ($age > 7 && $age <= 17) {
            return '90';
        } else if ($age > 7 && $age <= 17) {
            return '80';
        } else {
            return '70';
        }
    } else {
        $age1 = (new Carbon($date))->diffInYears('2010-01-01');
        $age2 = (new Carbon($date))->diffInYears('2000-01-01');
        $age3 = (new Carbon($date))->diffInYears('1990-01-01');
        $age4 = (new Carbon($date))->diffInYears('1980-01-01');
        if ($age <= $age1) {
            return '10';
        } else if ($age > $age1 && $age <= $age2) {
            return '00';
        } else if ($age > $age2 && $age <= $age3) {
            return '90';
        } else if ($age > $age3 && $age <= $age4) {
            return '80';
        } else {
            return '70';
        }
    }
}


function getDateFormatCharacter($date)
{
    $date = (new Carbon($date))->format('H:i');
    if ($date >= '00:00' && $date < '10:00') {
        return '10:00';
    } else if ($date >= '10:00' && $date < '12:00') {
        return '12:00';
    } else if ($date >= '12:00' && $date < '14:00') {
        return '14:00';
    } else if ($date >= '14:00' && $date < '16:00') {
        return '16:00';
    } else if ($date >= '16:00' && $date < '18:00') {
        return '18:00';
    } else if ($date >= '18:00' && $date < '20:00') {
        return '20:00';
    } else if ($date >= '20:00' && $date < '22:00') {
        return '22:00';
    } else {
        return '24:00';
    }
}

/**
 * 绩效清洗
 * @return string
 */
function teamBonusClean()
{
    $main_type = TeamPersonReward::MAIN_TYPE_CPE;
    $date = TeamBonusRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        //更新publication项目的投放时间
        $projectList = DB::connection('ar')->table('ar_product_list')
            ->whereRaw("online<>0")
            ->selectRaw("versionname,online")
            ->get();
        foreach ($projectList as $item) {
            TeamProject::query()->where('belong', $item->versionname)->update(['launch_date' => date('Y-m-d', $item->online / 1000)]);
        }

        //符合要求的点位当日的数据
        $faceCount1 = DB::connection('ar')->table('xs_face_count_log as fcl')
            ->join('ar_product_list as apl', 'belong', '=', 'versionname')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff', 'ao.bd_uid', '=', 'admin_staff.uid')
            ->whereRaw("date_format(fcl.date, '%Y-%m-%d')='$date' and apl.online<>0 and fcl.oid not in ('16', '19', '30', '31', '177','182','327','328','329','334','335','540') and aom.marketid <> '15' and aos.name<>'EXE颜镜店' and aos.name<>'星视度研发' and admin_staff.realname<>'颜镜店'")
            ->groupBy(DB::raw("date_format(fcl.date, '%Y-%m-%d'),fcl.oid,fcl.belong"))
            ->orderBy('date')
            ->orderBy('apl.id')
            ->orderBy('looknum', 'desc')
            ->selectRaw("date_format(fcl.date, '%Y-%m-%d') as date,apl.name as name,apl.online as online,fcl.belong as belong,sum(playernum7)as playernum7,sum(playernum15) as playernum15 ,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum");

        //每个节目每天的前100个点位数据汇总
        $faceCount = DB::connection('ar')->table(DB::raw("({$faceCount1->toSql()}) v"))
            ->selectRaw("@date:=date date,@name := name name,online,belong,sum(playernum7) as playernum7,sum(playernum15) as playernum15,sum(playernum21) as playernum21,sum(omo_outnum) as omo_outnum")
            ->whereRaw("(@gn :=(case when (@date = date and @name = name) then @gn + 1 else 1 end)) <= 100")
            ->groupBy('name')
            ->get();

        $count = [];
        foreach ($faceCount as $item) {
            //数据奖金池 B  $totalMoney
            $player7Money = $item->playernum7 * 0.01;
            $player15Money = $item->playernum15 * 0.02;
            $player21Money = $item->playernum21 * 0.05;
            $uCPAMoney = $item->omo_outnum * 0.2;
            $totalMoney = round($player7Money + $player15Money + $player21Money + $uCPAMoney, 2);

            //节目的投放日期
            $launchDate = date('Y-m-d', $item->online / 1000);

            $teamProject = TeamProject::query()->where('belong', $item->belong)->first();
            //投放时长 当前日期-投放日期
            $launchTime = $date >= $launchDate ? (new Carbon($date))->diffInDays($launchDate) : 1000;

            //新颖性系数T $factor
            $factor = 0;
            if ($teamProject) {
                if ($launchTime <= 30) {
                    //主管确认
                    if ($teamProject->status == 4) {
                        //提前制作时间 投放时间-上线时间
                        $advanceTime = $launchDate >= Carbon::parse($teamProject->online_date)->toDateString() ? (new Carbon($launchDate))->diffInDays($teamProject->online_date) : 0;
                        if ($advanceTime >= 90) {
                            $factor = 1.2;
                        }
                        if ($advanceTime >= 60 && $advanceTime < 90) {
                            $factor = 1.1;
                        }
                        if ($advanceTime < 60) {
                            if ($teamProject->individual_attribute) {
                                $factor = 1;
                            } else {
                                $factor = 0.9;
                            }
                        }
                    }
                    //运营确认
                    if ($teamProject->status == 3 && $teamProject->type == 0) {
                        if ($teamProject->individual_attribute) {
                            $factor = 1;
                        } else {
                            $factor = 0.9;
                        }
                    }
                }
                //已投放一个月
                if ($launchTime > 30 && $launchTime <= 60) {
                    $factor = 0.8;
                }
                //已投放两个月
                if ($launchTime > 60 && $launchTime <= 90) {
                    $factor = 0.7;
                }
                //已投放三个月
                if ($launchTime > 90 && $launchTime <= 120) {
                    $factor = 0.6;
                }
                //已投放四个月
                if ($launchTime > 120 && $launchTime <= 150) {
                    $factor = 0.5;
                }
                //已投放五个月
                if ($launchTime > 150 && $launchTime <= 180) {
                    $factor = 0.4;
                }
                //已投放六个月
                if ($launchTime > 210 && $launchTime <= 240) {
                    $factor = 0.3;
                }
                //已投放七个月
                if ($launchTime > 240 && $launchTime <= 270) {
                    $factor = 0.2;
                }
                //已投放八个月
                if ($launchTime > 270 && $launchTime <= 300) {
                    $factor = 0.1;
                }

            }

            if ($totalMoney > 0 && $factor > 0) {
                $count[] = [
                    'project_name' => $item->name,
                    'belong' => $item->belong,
                    'money' => $totalMoney, //B
                    'factor' => $factor,  //T
                    'date' => $date
                ];
            }
        }

        try {
            DB::beginTransaction();
            $result = [];

            $result[] = DB::table('team_bonuses')->insert($count);

            $data_copyright = DB::table('team_projects as tp')
                ->join('team_projects as tp2', 'tp.copyright_project_id', '=', 'tp2.id')
                ->join('team_project_members as tpm', 'tp2.id', '=', 'tpm.team_project_id')
                ->join('team_bonuses as tb', 'tp.belong', '=', 'tb.belong')
                ->leftjoin('contracts', 'contracts.id', '=', 'tp.contract_id')
                ->whereRaw("date_format(tb.date,'%Y-%m-%d')='$date' 
                and IFNull(contracts.amount,0) <= 0
                and tpm.type not in ('operation_quality','tester_quality')")
                ->selectRaw("tpm.user_id,tp.id as team_project_id,tp.project_name as project_name,tp.belong as belong,
                money * 0.2 as 'money',factor,rate,concat(tpm.type,'|copyright') as type");

            $data = DB::table('team_projects as tp')
                ->join('team_project_members as tpm', 'tp.id', '=', 'tpm.team_project_id')
                ->join('team_bonuses as tb', 'tp.belong', '=', 'tb.belong')
                ->leftjoin('contracts', 'contracts.id', '=', 'tp.contract_id')
                ->whereRaw("date_format(tb.date,'%Y-%m-%d')='$date'")
                ->selectRaw("user_id,tp.id as team_project_id,tp.project_name as project_name,tp.belong as belong,
                case  when IFNull(contracts.amount,0) <= 0 and tp.copyright_project_id > 0  then money * 0.8 else money end as 'money',
                factor,rate,tpm.type as type")
                ->unionAll($data_copyright)
                ->get();

            $rewards = [];
            $rewards_future = [];
            $date_future = Carbon::parse($date)->addMonth(3)->startOfMonth()->toDateString();

            $now = Carbon::now('PRC')->toDateTimeString();
            foreach ($data as $item) {
                if (in_array($item->type, ['tester_quality', 'operation_quality'])) {
                    $rewards_future[] = [
                        'user_id' => $item->user_id,
                        'project_name' => $item->project_name,
                        'belong' => $item->belong,
                        'type' => $item->type,
                        'main_type' => $main_type,
                        'total' => round($item->money * $item->factor * $item->rate, 6),
                        'date' => $date,
                        'get_date' => $date_future,
                        'status' => 0,
                        'team_project_id' => $item->team_project_id,
                        'created_at' => $now
                    ];
                } else {
                    $rewards[] = [
                        'user_id' => $item->user_id,
                        'project_name' => $item->project_name,
                        'belong' => $item->belong,
                        'type' => $item->type,
                        'main_type' => $main_type,
                        'total' => round($item->money * $item->factor * $item->rate, 6),
                        'date' => $date,
                        'get_date' => $date,
                    ];
                }
            }

            $result[] = DB::table('team_person_rewards')->insert($rewards);
            $result[] = DB::table('team_person_future_rewards')->insert($rewards_future);

            //判断当前日期是否是月份第一天，每个月份n第一天需要发第n-3个月的冻结奖金
            if ($date == Carbon::parse($date)->startOfMonth()->toDateString()) {

                //得到不同测试/运营的人，在本季度第一天的统计，取消扣除的奖金
                $quarterDate = Carbon::parse($date)->startOfQuarter()->toDateString();

                echo "quarterDate========" . $quarterDate;

                $users_bugs = DB::table('team_project_bug_records')
                    ->where('date', $quarterDate)->groupby("user_id", "duty")
                    ->selectRaw("user_id,duty,sum(bug_num) as num")
                    ->get();

                foreach ($users_bugs as $user_bug) {
                    if ($user_bug->num == 1) {
                        $start_date = Carbon::parse($quarterDate)->subMonths(3)->toDateString();
                        $end_date = Carbon::parse($quarterDate)->subMonths(2)->toDateString();
                    } else if ($user_bug->num == 2) {
                        $start_date = Carbon::parse($quarterDate)->subMonths(3)->toDateString();
                        $end_date = Carbon::parse($quarterDate)->subMonths(1)->toDateString();
                    } else if ($user_bug->num >= 3) {
                        $start_date = Carbon::parse($quarterDate)->subMonths(3)->toDateString();
                        $end_date = $quarterDate;
                    } else {
                        continue;
                    }

                    DB::table("team_person_future_rewards")
                        ->where('user_id', '=', $user_bug->user_id)
                        ->where('date', '>=', $start_date)
                        ->where('date', '<', $end_date)
                        ->where('type', '=', $user_bug->duty)
                        ->where('main_type', '=', $main_type)
                        ->where('status', '=', 0)
                        ->update(['status' => -1, 'updated_at' => $now]);
                }

                //发放当前需发放的rewards
                $future_rewards = DB::table("team_person_future_rewards")
                    ->where('get_date', '=', $date)
                    ->where('main_type', '=', $main_type)
                    ->where('status', '=', 0);

                $future_rewards_array = $future_rewards
                    ->selectRaw("user_id,project_name,belong,type,main_type,total,date,get_date")
                    ->get()->map(function ($value) {
                        return (array)$value;
                    })->toArray();
                $result[] = DB::table('team_person_rewards')->insert($future_rewards_array);

                $future_rewards->update(['status' => 1, 'updated_at' => $now]);
            }


            if (check_arr($result)) {
                echo "执行至" . $date . "\n";
                DB::commit();
                $date = (new Carbon($date))->addDay(1)->toDateString();
                TeamBonusRecord::create(['date' => $date]);
            } else {
                DB::rollBack();
                echo "执行至" . $date . ':fail' . json_encode($result) . "\n";
                return false;
            }
        } catch (Exception $e) {
            DB::rollBack();
            echo "执行至" . $date . ':出错，' . $e->getMessage() . "\n";
            return false;
        }
    }

    return true;
}

/**
 * PBI 绩效奖金清洗
 */
function PBIBonusClean()
{
    $main_type = TeamPersonReward::MAIN_TYPE_PBI;

    //查询符合条件的合同ID
    //1 收款合同 type = 0 ;
    //2 合同状态为 3|4 已审批|特批
    //3 pbi_money 为null
    $contract_ids = DB::table('contracts')
        ->whereRaw("type = 0 and status in (3,4) and pbi_money is null and amount > 0")
        ->pluck('id')->toArray();

    if (!$contract_ids) {
        echo "没有符合条件的合同\n";
        exit;
    }

    //符合要求合同的收款金额
    $contract_receipt = DB::table('contract_receive_dates as crd')
        ->leftJoin('invoice_receipts as ir', 'ir.id', '=', 'crd.invoice_receipt_id')
        ->leftJoin('contracts as ct', 'ct.id', '=', 'crd.contract_id')
        ->whereRaw('crd.receive_status = 1 and ir.claim_status = 1 and contract_id in (' . implode(',', $contract_ids) . ')')
        ->groupBy('crd.contract_id')
        ->selectRaw(" crd.contract_id,sum(ir.receipt_money) as 'receipt_money',ct.contract_number,ct.name,ct.special_num,ct.common_num,ct.amount");

    //符合要求合同的花费金额
    $contract_cost = DB::table('contract_costs as cc ')
        ->whereRaw("contract_id in (" . implode(',', $contract_ids) . ")")
        ->groupBy('contract_id')
        ->selectRaw("contract_id,sum(confirm_cost) as cost");


    //得到（合同总金额 amount <= 收款金额总和）合同，以及 节目制造营收JS值
    $contracts_with_pbi_money = DB::table(DB::raw("({$contract_receipt->toSql()}) V1"))
        ->leftJoin(DB::raw("({$contract_cost->toSql()}) V2"), 'V1.contract_id', '=', 'V2.contract_id')
        ->whereRaw(" V1.amount <= V1.receipt_money ")
        ->selectRaw("V1.* , V2.* , (V1.receipt_money - IFNULL(V2.cost,0)) as 'pbi_money',
        case when (common_num > 0 and special_num > 0)
             then (V1.receipt_money - IfNull(V2.cost,0) ) * 0.8 / special_num
             when (common_num = 0 and special_num > 0)
             then (V1.receipt_money - IfNull(V2.cost,0) ) / special_num
             else 0
             end as 'special_JS',
        case when common_num > 0
             then (V1.receipt_money - IfNull(V2.cost,0) ) * 0.2 / common_num
             else 0
             end as 'common_JS'
             ")
        ->get();

    foreach ($contracts_with_pbi_money->toArray() as $contract_with_pbi_money) {
        DB::beginTransaction();
        try {
            // 查询出符合条件的某合同的 未完成节目数量。
            $undone_project_num = DB::table('team_projects as tp ')
                ->whereRaw("contract_id = " . $contract_with_pbi_money->contract_id . " and case type when 1 then status != 4 else status != 3 end")
                ->selectRaw("count(*) as num")
                ->value("num");
            // 如果节目存在未完成的，则不进行发放
            if ($undone_project_num > 0) {
                continue;
            }

            //查询出该合同的所有相关节目（不同节目类型，不同的JS）,原创团队与现团队的PBI
            $data_copyright = DB::table('team_projects as tp')
                ->join('team_project_members as tpm', 'tp.copyright_project_id', '=', 'tpm.team_project_id')
                ->whereRaw("tp.contract_id = " . $contract_with_pbi_money->contract_id . " and tpm.type not in ('" . implode("','", TeamProjectMember::$team_quality) . "')")
                ->selectRaw("tpm.user_id,tp.id as team_project_id,tp.project_name as project_name,tp.belong as belong,
	            case tp.individual_attribute when 2 then " . $contract_with_pbi_money->special_JS . " when 3 then " . $contract_with_pbi_money->common_JS . " else null end as 'JS',
	            case 
	            	when tpm.type in ('" . implode("','", TeamProjectMember::$team_zhizao) . "') then 0.25 
	            	when tpm.type in ('" . implode("','", TeamProjectMember::$team_it) . "') then 0.125
	            	else 0 
 	            end as 'js_rate',
 	            0.2 as 'copyright_rate',
	            tpm.rate,concat(tpm.type,'|copyright') as type");

            $data = DB::table('team_projects as tp')
                ->join('team_project_members as tpm', 'tp.id', '=', 'tpm.team_project_id')
                ->whereRaw("tp.contract_id = " . $contract_with_pbi_money->contract_id . " and tpm.type not in ('" . implode("','", TeamProjectMember::$team_quality) . "')")
                ->selectRaw("tpm.user_id,tp.id as team_project_id,tp.project_name as project_name,tp.belong as belong,
	            case tp.individual_attribute when 2 then " . $contract_with_pbi_money->special_JS . " when 3 then " . $contract_with_pbi_money->common_JS . " else null end as 'JS',
	            case 
	            	when tpm.type in ('" . implode("','", TeamProjectMember::$team_zhizao) . "') then 0.25 
	            	when tpm.type in ('" . implode("','", TeamProjectMember::$team_it) . "') then 0.125
	            	else 0 
 	            end as 'js_rate',
 	            case when (tp.copyright_project_id is not null) then 0.8 else 1 end as 'copyright_rate',
	            tpm.rate,tpm.type as type")
                ->unionAll($data_copyright)
                ->get();

            $now = Carbon::now('PRC')->toDateTimeString();
            $rewards = [];
            foreach ($data as $item) {
                $total = round($item->JS * $item->js_rate * $item->copyright_rate * $item->rate, 6);
                if ($total > 0) {
                    $rewards[] = [
                        'user_id' => $item->user_id,
                        'project_name' => $item->project_name,
                        'belong' => $item->belong,
                        'type' => $item->type,
                        'main_type' => $main_type,
                        'total' => $total,
                        'date' => $now,
                        'get_date' => $now
                    ];
                }
            }
            DB::table('team_person_rewards')->insert($rewards);
            //修改改合同的状态
            DB::table('contracts')->where('id', '=', $contract_with_pbi_money->contract_id)
                ->update(['pbi_money' => $contract_with_pbi_money->pbi_money, 'pbi_date' => $now]);

            DB::commit();
            echo "PBI 绩效执行完成！";
            exit;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            exit;
        }
    }

}