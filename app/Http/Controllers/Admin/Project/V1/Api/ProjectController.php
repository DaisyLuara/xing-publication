<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectTransformer;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectRequest;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request, Project $project)
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
        $project = $query->where('name', 'like', "%{$request->name}%")
            ->orderBy('clientdate', 'desc')
            ->paginate(10);
        return $this->response->paginator($project, new ProjectTransformer());
    }

    public function store(ProjectRequest $request, Project $project)
    {
        $data = $request->all();
        $query = $project->query();
        $query->create(array_merge(['date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $data));
        return $this->response->noContent();
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->all();
        $query = $project->query();
        $query->where('id', '=', $data['id'])->update($data);
        return $this->response->noContent();
    }
}
