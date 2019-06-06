<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectLaunchTransformer;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectLaunchRequest;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class ProjectLaunchController extends Controller
{
    public function index(Request $request, ProjectLaunch $projectLaunch): Response
    {

        $user = $this->user();
        $ar_user_z = getArUserZ($user, $request);
        $query = $projectLaunch->query();

        if ($ar_user_z) {
            $query->whereHas('point', static function ($query) use ($ar_user_z) {
                $query->where('bd_z', '=', $ar_user_z);
            });
        }

        if ($request->has('scene_id')) {
            $scene_id = $request->get('scene_id');
            $query->whereHas('point', static function ($query) use ($scene_id) {
                $query->where('sid', '=', $scene_id);
            });
        }

        if ($request->has('project_name')) {
            $project_name = $request->get('project_name');
            $query->whereHas('project', static function ($query) use ($project_name) {
                $query->where('name', 'like', "%$project_name%");
            });
        }

        if ($request->has('market_id')) {
            $market_id = $request->get('market_id');
            $query->whereHas('point', static function ($query) use ($market_id) {
                $query->where('marketid', '=', $market_id);
            });
        }

        if ($request->has('area_id')) {
            $area_id = $request->get('area_id');
            $query->whereHas('point', static function ($query) use ($area_id) {
                $query->whereHas('market', static function ($query) use ($area_id) {
                    $query->where('areaid', '=', $area_id);
                });
            });
        }

        if ($request->has('tpl_name')) {
            $query->where($request->get('tpl_name'), '!=', 0);
        }

        if ($request->has('tpl_id')) {
            $tplId = $request->get('tpl_id');
            $query->where('day1_tvid', '=', $tplId)
                ->orWhere('day2_tvid', '=', $tplId)
                ->orWhere('day3_tvid', '=', $tplId)
                ->orWhere('day4_tvid', '=', $tplId)
                ->orWhere('day5_tvid', '=', $tplId)
                ->orWhere('day6_tvid', '=', $tplId)
                ->orWhere('day7_tvid', '=', $tplId)
                ->orWhere('div_tvid', '=', $tplId)
                ->orWhere('weekday_tvid', '=', $tplId)
                ->orWhere('weekend_tvid', '=', $tplId);
        }


        if ($request->has('ids')) {
            $ids = explode(',', $request->get('ids'));
            $query->whereIn('tvoid', $ids);
        }

        if ($request->has('visiable')) {
            $visiable = $request->get('visiable');
            $query->whereHas('point', static function ($query) use ($visiable) {
                $query->where('visiable', '=', $visiable);
            });
        }

        $projects = $query->orderBy('tvoid', 'desc')->paginate(10);
        return $this->response()->paginator($projects, new ProjectLaunchTransformer());

    }

    public function store(ProjectLaunchRequest $request, ProjectLaunch $projectLaunch)
    {
        $launch = $request->all();
        $query = $projectLaunch->query();

        $oids = $launch['oids'];
        unset($launch['oids']);

        foreach ($oids as $oid) {
            $query->create(array_merge(['oid' => $oid], $launch));
        }

        activity('create_product_launch')
            ->causedBy($this->user())
            ->performedOn($projectLaunch)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('批量新增节目投放');

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(ProjectLaunchRequest $request, ProjectLaunch $projectLaunch): Response
    {

        $launch = $request->all();
        $tvoids = $launch['tvoids'];

        unset($launch['tvoids'], $launch['oid']);

        foreach ($tvoids as $tvoid) {
            $query = $projectLaunch->query();
            $query->where(['tvoid' => $tvoid])->update($launch);
        }

        activity('create_product_launch')
            ->causedBy($this->user())
            ->performedOn($projectLaunch)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('批量编辑节目投放');

        return $this->response()->noContent();
    }
}
