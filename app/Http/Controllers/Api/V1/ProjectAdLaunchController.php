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
        if ($request->piid) {
            $piid = $request->piid;
            $query->whereHas('project', function ($q) use ($piid) {
                $q->where('id', '=', $piid);
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
