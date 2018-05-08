<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Project;
use App\Transformers\ProjectTransformer;
use Illuminate\Http\Request;

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
        $user = $this->user();
        $arUserId = getArUserID($user, $request);
        $query = $project->query();
        if ($arUserId) {
            $query->whereHas('points', function ($q) use ($arUserId) {
                $q->whereHas('arUsers', function ($q) use ($arUserId) {
                    $q->where('admin_staff.uid', '=', $arUserId);
                });
            });
        }

        if ($request->alias) {
            $query->where('versionname', '=', $request->alias);
        }
        $project = $query->where('name', 'like', "%{$request->name}%")->get();
        return $this->response->collection($project, new ProjectTransformer());
    }
}
