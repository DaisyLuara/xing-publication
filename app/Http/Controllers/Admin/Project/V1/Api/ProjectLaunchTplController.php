<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectLaunchTplTransformer;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectLaunchTplRequest;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectLaunchTplController extends Controller
{

    public function index(Request $request, ProjectLaunchTpl $projectLaunchTpl)
    {
        $query = $projectLaunchTpl->query();
        if ($request->name) {
            $query->where('name', 'like', "%" . $request->name . "%");
        }

        $projectLaunchTpl = $query->paginate(10);

        return $this->response->paginator($projectLaunchTpl, new ProjectLaunchTplTransformer());

    }

    public function store(ProjectLaunchTplRequest $request, ProjectLaunchTpl $projectLaunchTpl)
    {
        $projectLaunchTpl->fill($request->input())->save();
        return $this->response->item($projectLaunchTpl, new ProjectLaunchTplTransformer())
            ->setStatusCode(201);
    }

    public function update(ProjectLaunchTplRequest $request, ProjectLaunchTpl $projectLaunchTpl)
    {
        $projectLaunchTpl->update($request->all());
        return $this->response->item($projectLaunchTpl, new ProjectLaunchTplTransformer())
            ->setStatusCode(201);
    }
}
