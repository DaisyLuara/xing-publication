<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\ProjectLaunch;
use App\Transformers\ProjectLaunchTransformer;
use App\Http\Requests\Api\V1\ProjectLaunchRequest;

class ProjectLaunchController extends Controller
{
    public function index(Request $request, ProjectLaunch $projectLaunch)
    {

        $user = $this->user();
        $ar_user_id = getArUserID($user, $request);
        $query = $projectLaunch->query();

        if ($ar_user_id) {
            $query->whereHas('point', function ($query) use ($ar_user_id) {
                $query->whereHas('arUsers', function ($query) use ($ar_user_id) {
                    $query->where('admin_staff.uid', '=', $ar_user_id);
                });
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

        if ($request->ids) {
            $ids = explode(',', $request->ids);
            $query->whereIn('tvoid', $ids);
        }

        $projects = $query->orderBy('tvoid', 'desc')->paginate(10);
        return $this->response->paginator($projects, new ProjectLaunchTransformer());

    }

    public function store(ProjectLaunchRequest $request, ProjectLaunch $projectLaunchLocal)
    {
        $launch = $request->all();
        $query = $projectLaunchLocal->query();

        $oids = $launch['oids'];
        unset($launch['oids']);

        foreach ($oids as $oid) {
            $query->create(array_merge(['oid' => $oid], $launch));
        }

        return $this->response->noContent();
    }

    public function update(ProjectLaunchRequest $request, ProjectLaunch $projectLaunchLocal)
    {

        $launch = $request->all();
        $tvoids = $launch['tvoids'];

        unset($launch['tvoids']);
        unset($launch['oid']);

        foreach ($tvoids as $tvoid) {
            $query = $projectLaunchLocal->query();
            $query->where(['tvoid' => $tvoid])->update($launch);
        }

        return $this->response->noContent();
    }
}
