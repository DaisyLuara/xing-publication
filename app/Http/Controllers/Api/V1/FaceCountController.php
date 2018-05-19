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
    /**
     * @param Request $request
     * @param FaceCount $faceCount
     * @return mixed
     */
    public function index(Request $request, FaceCount $faceCount)
    {
        $query = $this->queryInit($request, $faceCount->query());
        $default = $this->getDefaultParams($request);

        $query->join('avr_official', 'avr_official.oid', '=', 'face_count_log.oid')
            ->join('avr_official_market', 'avr_official.marketid', '=', 'avr_official_market.marketid')
            ->join('avr_official_area', 'avr_official_area.areaid', '=', 'avr_official.areaid');
        $faceCount = $query->whereRaw("str_to_date(face_count_log.date, '%Y-%m-%d') BETWEEN '" . $default['startDate'] . "' AND '" . $default['endDate'] . "'")
            ->where('face_count_log.belong', '=', $default['alias'])
            ->selectRaw('fclid as id,
                         looknum,
                         playernum,
                         lovenum,
                         outnum,
                         scannum,
                         avr_official.name as point_name,
                         avr_official_market.name as market_name,
                         avr_official_area.name as area_name,
                         face_count_log.date as created_at')
            ->where('fclid', '>', 0)
            ->orderBy('avr_official_area.areaid', 'desc')
            ->orderBy('avr_official_market.marketid', 'desc')
            ->orderBy('avr_official.oid', 'desc')
            ->paginate(5);

        return $this->response->paginator($faceCount, new FaceCountTransformer());
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
            $query->where('face_count_log.oid', '=', $request->point_id);
        }

        if ($request->scene_id) {
            $scene_id = $request->scene_id;
            $query->whereHas('point', function ($query) use ($scene_id) {
                $query->where('sid', '=', $scene_id);
            });
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

    public function getTenUser()
    {
        $date = Carbon::now()->toDateString();
        $faceCount = DB::connection('ar')->table('face_count_log as fcl')
            ->join('admin_per_oid as apo', 'fcl.oid', '=', 'apo.oid')
            ->join('avr_official as ao', 'fcl.oid', '=', 'ao.oid')
            ->join('avr_official_area as aoa', 'ao.areaid', '=', 'aoa.areaid')
            ->join('avr_official_market as aom', 'ao.marketid', '=', 'aom.marketid')
            ->whereRaw(" date_format(fcl.date,'%Y-%m-%d') between '$date' and '$date' ")
            ->where('belong', '<>', 'all')
            ->whereNotIn('fcl.oid', [16, 19, 30, 31, 335, 334, 329, 328, 327])
            ->orderBy('looknum')
            ->limit(10)
            ->selectRaw("  apo.uid as uid,aoa.name as areaName,aom.name as marketName,ao.name as pointName,looknum")
            ->get();
        $data = [$date];
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
