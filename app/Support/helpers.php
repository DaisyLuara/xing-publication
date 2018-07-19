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

        $timeArray = [7, 20, 30];

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
                'playernum20' => $item->playernum20,
                'playernum30' => $item->playernum30,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'playernum7' => $item->playernum7,
                'playernum20' => $item->playernum20,
                'playernum30' => $item->playernum30,
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

function mergeActiveAndLook()
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
            ->selectRaw("oid,belong,playernum7,playernum20,playernum30");

        $data = DB::connection('ar')->table(DB::raw("({$sql1->toSql()}) as a"))
            ->join(DB::raw("({$sql2->toSql()}) as b"), function ($join) {
                $join->on('a.oid', '=', 'b.oid');
                $join->on('a.belong', '=', 'b.belong');
            }, null, null, 'left')
            ->selectRaw("a.oid as oid,a.belong as belong,looknum,playernum7,playernum20,playernum30,playernum,outnum,scannum,lovenum")
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'looknum' => $item->looknum,
                'playernum7' => $item->playernum7 ? $item->playernum7 : 0,
                'playernum20' => $item->playernum20 ? $item->playernum20 : 0,
                'playernum30' => $item->playernum30 ? $item->playernum30 : 0,
                'playernum' => $item->playernum,
                'outnum' => $item->outnum,
                'scannum' => $item->scannum,
                'lovenum' => $item->lovenum,
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

        $century00 = "when age > 8 and age <= 18 then '00'";
        $century90 = "when age > 18 and age <= 28 then '90' ";
        $century80 = "when age > 18 and age <= 38 then '80' ";
        $century70 = "when age > 38 and age <= 48 then '70' ";
        $century = $century00 . $century90 . $century80 . $century70;

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
            $item['time'] = (new Carbon($item['time']))->addMinutes(30)->format('H:i');
            $item['date'] = $date;
            $item['clientdate'] = strtotime($date) * 1000;
            $count[] = $item;
        }
        foreach ($data as $item) {
            $item = json_decode(json_encode($item), true);
            $item['time'] = (new Carbon($item['time']))->addMinutes(30)->format('H:i');
            $item['date'] = $date;
            $item['clientdate'] = strtotime($date) * 1000;
            $count[] = $item;
        }
        $count = array_chunk($count, 8000);
        for ($i = 0; $i < count($count); $i++) {
            DB::connection('ar')->table('xs_face_character')->insert($count[$i]);
        }
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
}

function faceCharacterCountClean()
{
    $date = FaceCharacterRecord::query()->max('date');
    $date = (new Carbon($date))->format('Y-m-d');
    $currentDate = Carbon::now()->toDateString();
    while ($date < $currentDate) {
        $clientDate = strtotime($date . ' 00:00:00') * 1000;
        $time1 = " when time >'00:00' and time <='10:00' then '10:00'";
        $time2 = " when time >'10:00' and time <='12:00' then '12:00'";
        $time3 = " when time >'12:00' and time <='14:00' then '14:00'";
        $time4 = " when time >'14:00' and time <='16:00' then '16:00'";
        $time5 = " when time >'16:00' and time <='18:00' then '18:00'";
        $time6 = " when time >'18:00' and time <='20:00' then '20:00'";
        $time7 = " when time >'20:00' and time <='22:00' then '22:00'";
        $time8 = " when time >'22:00' or time ='00:00' then '24:00'";
        $timeSql = $time1 . $time2 . $time3 . $time4 . $time5 . $time6 . $time7 . $time8;

        $sql = DB::connection('ar')->table('xs_face_character')
            ->whereRaw("clientdate='$clientDate'")
            ->groupBy(DB::raw("oid,belong,times,century,gender"))
            ->selectRaw("oid,belong,case" . $timeSql . " else 0 end as times,century,gender,sum(looknum) as looknum");

        $sum1 = "sum(if(century = '00' and gender = 'Male', looknum, 0))   as century00_bnum,";
        $sum2 = " sum(if(century = '00' and gender = 'Female', looknum, 0)) as century00_gnum,";
        $sum3 = " sum(if(century = '90' and gender = 'Male', looknum, 0))   as century90_bnum,";
        $sum4 = " sum(if(century = '90' and gender = 'Female', looknum, 0)) as century90_gnum,";
        $sum5 = " sum(if(century = '80' and gender = 'Male', looknum, 0))   as century80_bnum,";
        $sum6 = " sum(if(century = '80' and gender = 'Female', looknum, 0)) as century80_gnum,";
        $sum7 = " sum(if(century = '70' and gender = 'Male', looknum, 0))   as century70_bnum,";
        $sum8 = " sum(if(century = '70' and gender = 'Female', looknum, 0)) as century70_gnum,";
        $sum9 = " sum(if(century = '0' and gender = 'Male', looknum, 0))    as century0_bnum,";
        $sum10 = " sum(if(century = '0' and gender = 'Female', looknum, 0)) as century0_gnum";
        $sum = $sum1 . $sum2 . $sum3 . $sum4 . $sum5 . $sum6 . $sum7 . $sum8 . $sum9 . $sum10;
        $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->groupBy(DB::raw('oid,belong,times'))
            ->selectRaw("oid,belong,times," . $sum)
            ->get();

        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'time' => $item->times,
                'century00_bnum' => $item->century00_bnum ? $item->century00_bnum : 0,
                'century00_gnum' => $item->century00_gnum ? $item->century00_gnum : 0,
                'century90_bnum' => $item->century90_bnum ? $item->century90_bnum : 0,
                'century90_gnum' => $item->century90_gnum ? $item->century90_gnum : 0,
                'century80_bnum' => $item->century80_bnum ? $item->century80_bnum : 0,
                'century80_gnum' => $item->century80_gnum ? $item->century80_gnum : 0,
                'century70_bnum' => $item->century70_bnum ? $item->century70_bnum : 0,
                'century70_gnum' => $item->century70_gnum ? $item->century70_gnum : 0,
                'century0_bnum' => $item->century0_bnum ? $item->century0_bnum : 0,
                'century0_gnum' => $item->century0_gnum ? $item->century0_gnum : 0,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        $count = array_chunk($count, 8000);
        for ($i = 0; $i < count($count); $i++) {
            DB::connection('ar')->table('xs_face_character_count')
                ->insert($count[$i]);
        }
        $date = (new Carbon($date))->addDay(1)->toDateString();
    }
    FaceCharacterRecord::create(['date' => $currentDate]);
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
