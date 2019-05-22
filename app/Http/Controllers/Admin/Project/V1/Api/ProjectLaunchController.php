<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectLaunchTransformer;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectLaunchRequest;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectLaunchController extends Controller
{
    public function index(Request $request, ProjectLaunch $projectLaunch)
    {

        $user = $this->user();
        $ar_user_z = getArUserZ($user, $request);
        $query = $projectLaunch->query();

        if ($ar_user_z) {
            $query->whereHas('point', function ($query) use ($ar_user_z) {
                $query->where('bd_z', '=', $ar_user_z);
            });
        }

        if ($request->scene_id) {
            $scene_id = $request->scene_id;
            $query->whereHas('point', function ($query) use ($scene_id) {
                $query->where('sid', '=', $scene_id);
            });
        }

        if ($request->project_name) {
            $project_name = $request->project_name;
            $query->whereHas('project', function ($query) use ($project_name) {
                $query->where('name', 'like', "%$project_name%");
            });
        }

        if ($request->market_id) {
            $market_id = $request->market_id;
            $query->whereHas('point', function ($query) use ($market_id) {
                $query->where('marketid', '=', $market_id);
            });
        }

        if ($request->area_id) {
            $area_id = $request->area_id;
            $query->whereHas('point', function ($query) use ($area_id) {
                $query->whereHas('market', function ($query) use ($area_id) {
                    $query->where('areaid', '=', $area_id);
                });
            });
        }

        if ($request->tpl_name) {
            $query->where($request->tpl_name, '!=', 0);
        }

        if ($request->tpl_id) {
            $tplId = $request->tpl_id;
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


        if ($request->ids) {
            $ids = explode(',', $request->ids);
            $query->whereIn('tvoid', $ids);
        }

        if ($request->has('visiable')) {
            $visiable = $request->visiable;
            $query->whereHas('point', function ($query) use ($visiable) {
                $query->where('visiable', '=', $visiable);
            });
        }

        $projects = $query->orderBy('tvoid', 'desc')->paginate(10);
        return $this->response->paginator($projects, new ProjectLaunchTransformer());

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

        activity('project_launch')->on($projectLaunch)->withProperties($request->all())->log('批量增加节目投放');

        return $this->response->noContent();
    }

    public function update(ProjectLaunchRequest $request, ProjectLaunch $projectLaunch)
    {

        $launch = $request->all();
        $tvoids = $launch['tvoids'];

        unset($launch['tvoids']);
        unset($launch['oid']);

        foreach ($tvoids as $tvoid) {
            $query = $projectLaunch->query();
            $query->where(['tvoid' => $tvoid])->update($launch);
        }

        activity('project_launch')->on($projectLaunch)->withProperties($request->all())->log('批量修改节目投放');

        return $this->response->noContent();
    }
}
