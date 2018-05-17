<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ProjectRequest;
use App\Models\Project;
use App\Transformers\ProjectTransformer;
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
        $names = explode(PHP_EOL, $request->name);
        unset($data['name']);

        $query = $project->query();
        foreach ($names as $name) {
            $query->create(array_merge(['name' => $name, 'date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $data));
        }
        return $this->response->noContent();
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->all();
        $ids = $request->ids;
        unset($data['ids']);

        $query = $project->query();
        foreach ($ids as $id) {
            $query->where('id', '=', $id)->update($data);
        }
        return $this->response->noContent();
    }
}
