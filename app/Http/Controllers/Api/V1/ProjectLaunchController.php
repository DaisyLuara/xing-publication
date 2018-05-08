<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ProjectLaunchLocal;
use Illuminate\Http\Request;
use App\Models\ProjectLaunch;
use App\Transformers\ProjectLaunchTransformer;

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

        $projects = $query->paginate(10);
        return $this->response->paginator($projects, new ProjectLaunchTransformer());

    }

    //测试环境 使用 本地数据更新
    public function store(Request $request, ProjectLaunchLocal $projectLaunchLocal)
    {
//        $launches = $request->all();
//        if (count($launches)) {
//            $query = $projectLaunchLocal->query();
//            foreach ($launches as $launch) {
//                $query->create($launch);
//            }
//        }
//        return $this->response->noContent();
    }

    public function update(Request $request, ProjectLaunchLocal $projectLaunchLocal)
    {

//        $launches = $request->all();
//        if (count($launches)) {
//            $query = $projectLaunchLocal->query();
//            foreach ($launches as $launch) {
//                $query->update(['tvoid' => $launch['tvoid']], $launches);
//            }
//        }
//        return $this->response->noContent();
    }
}
