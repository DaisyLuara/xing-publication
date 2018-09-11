<?php

namespace App\Http\Controllers\Admin\Point\V1\Api;

use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Point\V1\Request\PointRequest;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function map(PointRequest $request, Point $point)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $distance = $request->distance ? $request->distance : 1;

        $query = $point->query();
        if ($request->date && $request->date == 'today') {
            $startDate = Carbon::now()->startOfDay()->toDateTimeString();
            $endDate = Carbon::now()->endOfDay()->toDateTimeString();
            $query->whereRaw("str_to_date(face_count_log.date, '%Y-%m-%d %H:%i:%s') BETWEEN '" . $startDate . "' AND '" . $endDate . "'");
        }
        $points = $query->join('face_count_log', 'avr_official.oid', '=', 'face_count_log.oid')
            ->selectRaw("sum(looknum) as count,lat,lng,avr_official.oid")
            ->whereRaw("ACOS(SIN(($lat * 3.1415) / 180) * SIN((lat * 3.1415) / 180) + COS(($lat * 3.1415) / 180) * COS((lat * 3.1415) / 180) *COS(($lng * 3.1415) / 180 - (lng * 3.1415) / 180)) * 6380 <= $distance")
            ->where('face_count_log.belong', '=', 'all')
            ->groupBy('avr_official.oid')
            ->get();

        return $this->response->collection($points, new PointTransformer());

    }

    public function index(Request $request, Point $point)
    {
        $query = $point->query();

        //点位名称
        if ($request->has('point_name')) {
            $query->where('name', 'like', '%' . $request->point_name . '%');
        }

        //场地名称
        if ($request->has('marketid')) {
            $query->where('marketid', '=', $request->marketid);
        }

        //区域
        if ($request->has('areaid')) {
            $query->where('areaid', '=', $request->areaid);
        }

        //点位类型
        if ($request->has('contract_type')) {
            $contractType = $request->contract_type;
            $query->whereHas('contract', function ($query) use ($contractType) {
                $query->where('type', '=', $contractType);
            });
        }

        //合作模式
        if ($request->has('contract_mode')) {
            $contractMode = $request->contract_mode;
            $query->whereHas('contract', function ($query) use ($contractMode) {
                $query->where('mode', '=', $contractMode);
            });
        }

        //点位权限
        if ($request->has('share_users')) {
            $shareUsers = explode(',', $request->share_users);
            $query->whereHas('share', function ($query) use ($shareUsers) {
                foreach ($shareUsers as $shareUser) {
                    $query->where("$shareUser", '=', 1);
                }
            });
        }
        $points = $query->paginate(10);
        return $this->response->paginator($points, new PointTransformer());
    }

    public function show(Point $point)
    {
        return $this->response->item($point, new PointTransformer());
    }

    public function store(PointRequest $request, Point $point)
    {
        /**
         * @todo check market area
         */
        $point->fill($request->all())->saveOrFail();

        if ($request->has('contract')) {
            $point->contract()->create($request->contract);
        }

        if ($request->has('share')) {
            $point->share()->create($request->share);
        }

        return $this->response->item($point, new PointTransformer());
    }

    public function update(PointRequest $request, Point $point)
    {
        $point->update($request->all());
        if ($request->has('contract')) {
            $contract = $request->contract;
            if (isset($contract['oid'])) {
                unset($contract['oid']);
            }

            $point->contract()->getResults()->update($contract);
        }

        if ($request->has('share')) {
            $share = $request->share;
            if (isset($share['oid'])) {
                unset($share['oid']);
            }
            $point->share()->getResults()->update($share);
        }

        return $this->response->item($point, new PointTransformer());
    }
}
