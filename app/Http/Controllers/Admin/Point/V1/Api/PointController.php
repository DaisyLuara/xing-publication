<?php

namespace App\Http\Controllers\Admin\Point\V1\Api;

use App\Http\Controllers\Admin\Attribute\V1\Models\Attribute;
use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Point\V1\Request\PointRequest;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\User\V1\Models\ArUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function map(PointRequest $request, Point $point): Response
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $distance = $request->get('distance') ?: 1;

        $query = $point->query();
        if ($request->get('date') && $request->get('date') === 'today') {
            $startDate = Carbon::now()->startOfDay()->toDateTimeString();
            $endDate = Carbon::now()->endOfDay()->toDateTimeString();
            $query->whereRaw("str_to_date(face_count_log.date, '%Y-%m-%d %H:%i:%s') BETWEEN '" . $startDate . "' AND '" . $endDate . "'");
        }
        $points = $query->join('face_count_log', 'avr_official.oid', '=', 'face_count_log.oid')
            ->selectRaw('sum(looknum) as count,lat,lng,avr_official.oid')
            ->whereRaw("ACOS(SIN(($lat * 3.1415) / 180) * SIN((lat * 3.1415) / 180) + COS(($lat * 3.1415) / 180) * COS((lat * 3.1415) / 180) *COS(($lng * 3.1415) / 180 - (lng * 3.1415) / 180)) * 6380 <= $distance")
            ->where('face_count_log.belong', '=', 'all')
            ->groupBy('avr_official.oid')
            ->get();

        return $this->response()->collection($points, new PointTransformer());

    }

    public function index(Request $request, Point $point): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $arUserZ = getArUserZ($user, $request);

        $query = $point->query();

        if ($user->hasRole('user|bd-manager')) {
            if (!$arUserZ) {
                $arUserZ = '0';
            }
            $query->where('bd_z', '=', $arUserZ);
        }

        //点位名称
        if ($request->has('point_name')) {
            $query->where('name', 'like', '%' . $request->get('point_name') . '%');
        }

        //场地名称
        if ($request->has('marketid')) {
            $query->where('marketid', '=', $request->get('marketid'));
        }

        //区域
        if ($request->has('areaid')) {
            $query->where('areaid', '=', $request->get('areaid'));
        }

        //点位类型
        if ($request->has('contract_type')) {
            $contractType = $request->get('contract_type');
            $query->whereHas('contract', static function ($query) use ($contractType) {
                $query->where('type', '=', $contractType);
            });
        }

        //合作模式
        if ($request->has('contract_mode')) {
            $contractMode = $request->get('contract_mode');
            $query->whereHas('contract', static function ($query) use ($contractMode) {
                $query->where('mode', '=', $contractMode);
            });
        }

        //点位权限
        if ($request->has('share_users')) {
            $shareUsers = explode(',', $request->get('share_users'));
            $query->whereHas('share', static function ($query) use ($shareUsers) {
                foreach ($shareUsers as $shareUser) {
                    $query->where((string)$shareUser, '=', 1);
                }
            });
        }
        $points = $query->orderByDesc('date')->paginate(10);
        return $this->response()->paginator($points, new PointTransformer());
    }

    public function show($id, Request $request): Response
    {
        $query = Point::query();

        $user = $this->user();
        $arUserZ = getArUserZ($user, $request);

        if ($arUserZ) {
            $query->where('bd_z', '=', $arUserZ);
        }

        $point = $query->where('oid', $id)->first();
        if (!$point) {
            abort(404);
        }

        return $this->response()->item($point, new PointTransformer());
    }

    public function store(PointRequest $request, Point $point)
    {
        /** @var  User $user */
        $user = $this->user();
        if (!$user->hasRole('user|bd-manager')) {
            abort(403, '无操作权限');
        }
        if (!$user->z) {
            abort(500, '无用户标识');
        }

        $arUser = ArUser::query()->where('z', $user->z)->first();
        $arSite = ArUser::query()->where('z', $request->get('site_z'))->first();
        $point->fill(array_merge($request->all(), ['bd_uid' => $arUser->uid, 'bd_z' => $user->z, 'site_uid' => $arSite->uid]))->saveOrFail();
        $point->attribute()->attach($request->get('attribute_id'));

        if ($request->has('contract')) {
            $point->contract()->create($request->get('contract'));
        }

        if ($request->has('share')) {
            $point->share()->create($request->get('share'));
        }

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(PointRequest $request, Point $point): Response
    {
        $node = Attribute::query()->where('name', '业态')->first();
        $attribute = $point->attribute()->get();
        /** @var \Baum\Node $item */
        foreach ($attribute as $item) {
            if ($item->isDescendantOf($node)) {
                $point->attribute()->detach($item->id);
            }
        }
        $point->attribute()->attach($request->get('attribute_id'));

        $point->update($request->all());
        if ($request->has('contract')) {
            $contract = $request->get('contract');
            if (isset($contract['oid'])) {
                unset($contract['oid']);
            }

            if ($point_contract = $point->contract()->getResults()) {
                $point_contract->update($contract);
            } else {
                $point->contract()->create($contract);
            }
        }

        if ($request->has('share')) {
            $share = $request->get('share');
            if (isset($share['oid'])) {
                unset($share['oid']);
            }

            if ($point_share = $point->share()->getResults()) {
                $point_share->update($share);
            } else {
                $point->share()->create($share);
            }

        }

        return $this->response()->item($point, new PointTransformer());
    }
}
