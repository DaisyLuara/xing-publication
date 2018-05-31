<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use EasyWeChat;
use EasyWeChat\Kernel\Messages\Text;

class FaceCountController extends Controller
{
    public function test()
    {
        $data = $this->getTenUser();
        /** @var EasyWeChat\OfficialAccount\Application $officialAccount */
        $officialAccount = EasyWeChat::officialAccount();
        for ($i = 0; $i < count($data); $i++) {
            $text = new Text('你负责的点位“' . $data[$i]['pointName'] . '”上周日均围观数处于倒数第' . ($i + 1) . '名，日均围观数：' . $data[$i]['looknum']);
//                $user = User::where('ar_user_id', '=', $data[$i]['uid']);
//                $openId = $user->openid;
            $openId = 'oNN6q0sZDI_OSTV6rl0rPeHjPgH8';
            $officialAccount->customer_service->message($text)->to($openId)->send();
        }
    }

    public function getTenUser()
    {
        $startDate = Carbon::now()->addDay(-12)->toDateString();
        $endDate = Carbon::now()->addDay(-5)->toDateString();
        $faceCount = DB::connection('ar')->table('face_count_log as fcl')
            ->join('admin_per_oid as apo', 'fcl.oid', '=', 'apo.oid')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '2018-04-01' and '2018-04-07' ")
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
