<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\WeChat\V1\Models\WeekRanking;
use Carbon\Carbon;
use DB;
use App\Jobs\WeekRankingJob;
use EasyWeChat;

class FaceCountController extends Controller
{
    public function aaa()
    {
        $startDate = '2018-04-01';
        $endDate = '2018-04-07';

        $marketPoint = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('sid', '=', 1)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->selectRaw("fcl.oid")
            ->get();
        $limitMarket = (floor($marketPoint->count() / 100) + 1) * 10;
        $faceCountMarket = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('ao.sid', '=', 1)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitMarket)
            ->selectRaw("  ao.bd_uid as uid,as.realname as userName,aos.sid as sceneId,aos.name as sceneName,fcl.oid as oid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();

        $cinemaPoint = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('sid', '=', 8)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->selectRaw("fcl.oid")
            ->get();
        $limitCinema = (floor($cinemaPoint->count() / 100) + 1) * 10;
        $faceCountCinema = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('ao.sid', '=', 8)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitCinema)
            ->selectRaw("  ao.bd_uid as uid,as.realname as userName,aos.sid as sceneId,aos.name as sceneName,fcl.oid as oid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();

        $otherPoint = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->where('sid', '=', 8)
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->selectRaw("fcl.oid")
            ->get();
        $limitOther = (floor($otherPoint->count() / 100) + 1) * 10;
        $faceCount_other = DB::connection('ar')->table('face_count_log as fcl')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->join('avr_official_scene as aos', 'ao.sid', '=', 'aos.sid')
            ->join('admin_staff as as', 'ao.bd_uid', '=', 'as.uid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('ao.sid', [1, 8])
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->groupBy('fcl.oid')
            ->orderBy('looknum')
            ->limit($limitOther)
            ->selectRaw("  ao.bd_uid as uid,as.realname as userName,aos.sid as sceneId,aos.name as sceneName,fcl.oid as oid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,sum(looknum) as looknum")
            ->get();

        $data = [];
        $i = 1;
        $faceCountMarket->each(function ($item) use (&$data, $startDate, $endDate, &$i) {
            $data[] = [
                'ar_user_id' => $item->uid,
                'ar_user_name' => $item->userName,
                'point_id' => $item->oid,
                'point_name' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'scene_id' => $item->sceneId,
                'scene_name' => $item->sceneName,
                'looknum_average' => round($item->looknum / 7, 0),
                'ranking' => $i,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'date' => Carbon::now()->toDateString(),
            ];
            $i++;
        });

        $i = 1;
        $faceCountCinema->each(function ($item) use (&$data, $startDate, $endDate, &$i) {
            $data[] = [
                'ar_user_id' => $item->uid,
                'ar_user_name' => $item->userName,
                'point_id' => $item->oid,
                'point_name' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'scene_id' => $item->sceneId,
                'scene_name' => $item->sceneName,
                'looknum_average' => round($item->looknum / 7, 0),
                'ranking' => $i,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'date' => Carbon::now()->toDateString(),
            ];
            $i++;
        });

        $i = 1;
        $faceCount_other->each(function ($item) use (&$data, $startDate, $endDate, &$i) {
            $data[] = [
                'ar_user_id' => $item->uid,
                'ar_user_name' => $item->userName,
                'point_id' => $item->oid,
                'point_name' => $item->areaName . '-' . $item->marketName . '-' . $item->pointName,
                'scene_id' => $item->sceneId,
                'scene_name' => $item->sceneName,
                'looknum_average' => round($item->looknum / 7, 0),
                'ranking' => $i,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'date' => Carbon::now()->toDateString(),
            ];
            $i++;
        });

        for ($i = 0; $i < count($data); $i++) {
            //WeekRankingJob::dispatch($data[$i])->onQueue('weekRanking');
            $officialAccount = EasyWeChat::officialAccount();
            $message = [
                'touser' => "oNN6q0sZDI_OSTV6rl0rPeHjPgH8",
                'template_id' => 'siyJMjigeMMNpXrFSsvz6rvrKQh9Gf5RcfbiVYFQFyY',
                'data' => [
                    'first' => '你好，你的上周点位排名情况如下',
                    'keyword1' => $data[$i]['point_name'],
                    'keyword2' => "日均围观数：" . $data[$i]['looknum_average'] . "\r\n" . "点位排名：倒数第" . $data[$i]['ranking'] . "\r\n" . "场景分类：" . $data[$i]['scene_name'] . "\r\n" . "时间区间：" . $data[$i]['start_date'] . "至" . $data[$i]['end_date'],
                    'remark' => '再接再厉',
                ]
            ];
            $officialAccount->template_message->send($message);
        }
        WeekRanking::query()->insert($data);
    }
}
