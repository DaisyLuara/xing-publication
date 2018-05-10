<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ProjectAdLaunch;
use App\Transformers\ProjectAdLaunchTransformer;
use Illuminate\Http\Request;

class ProjectAdLaunchController extends Controller
{
    public function index(Request $request, ProjectAdLaunch $projectAdLaunch)
    {
        $query = $projectAdLaunch->query();
        if ($request->project_id) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('id', '=', $request->project_id);
            });
        }

        if ($request->visiable) {
            $query->where('visiable', $request->visiable);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        $projectAdLaunch = $query->paginate(10);

        return $this->response->paginator($projectAdLaunch, new ProjectAdLaunchTransformer());
    }
}
