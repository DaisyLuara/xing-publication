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
        $table = $query->getModel()->getTable();

        $arUserID = getArUserID($this->user(), $request);
        handPointQuery($request, $query, $arUserID, true);
        if ($request->name) {
            $query->where("$table.name", 'like', "%" . $request->name . "%");
        }

        $projectLaunchTpl = $query->selectRaw($query->getModel()->getTable() . ".*")->orderBy('tvid', 'desc')->paginate(10);

        return $this->response->paginator($projectLaunchTpl, new ProjectLaunchTplTransformer());

    }

    public function store(ProjectLaunchTplRequest $request, ProjectLaunchTpl $projectLaunchTpl)
    {
        $fillData = $this->convert($request->all());
        $projectLaunchTpl->fill($fillData)->save();
        return $this->response->item($projectLaunchTpl, new ProjectLaunchTplTransformer())
            ->setStatusCode(201);
    }

    public function update(ProjectLaunchTplRequest $request, ProjectLaunchTpl $projectLaunchTpl)
    {
        $updateData = $this->convert($request->all());
        $projectLaunchTpl->update($updateData);
        return $this->response->item($projectLaunchTpl, new ProjectLaunchTplTransformer())
            ->setStatusCode(201);
    }

    private function convert($input)
    {
        $data = [];
        if (isset($input['point_id'])) {
            $data['oid'] = $input['point_id'];
        }

        if (isset($input['name'])) {
            $data['name'] = $input['name'];
        }
        return $data;

    }
}
