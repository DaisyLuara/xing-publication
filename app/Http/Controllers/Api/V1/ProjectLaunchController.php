<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ProjectLaunchRequest;
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

        $projects = $query->paginate(10);
        return $this->response->paginator($projects, new ProjectLaunchTransformer());

    }

    public function store(ProjectLaunchRequest $request, ProjectLaunchLocal $projectLaunchLocal)
    {


    }

    public function update()
    {

    }
}
