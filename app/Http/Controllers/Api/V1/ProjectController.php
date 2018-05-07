<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Transformers\ProjectTransformer;

class ProjectController extends Controller
{
    public function index(Request $request, Project $project)
    {
        $query = $project->query();
        $projects = $query->paginate(10);
        return $this->response->paginator($projects, new ProjectTransformer());
    }


    public function userProject(Request $request, Project $project)
    {
        $uid = 0;
        if ($this->user()->isAdmin() && $request->has('ar_user_id')) {
            $uid = $request->ar_user_id;
        } else if (!$this->user()->isAdmin()) {
            $uid = $this->user()->ar_user_id;
        }

        $query = $project->query();

        if ($uid) {
            $query->whereHas('points', function ($q) use ($uid) {
                $q->whereHas('arUsers', function ($q) use ($uid) {
                    $q->where('admin_staff.uid', '=', $uid);
                });
            });
        }

        $project = $query->where('name', 'like', "%{$request->project_name}%")
            ->selectRaw('name,versionname')
            ->get();
        return $this->response->collection($project, new ProjectTransformer());
    }
}
