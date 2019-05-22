<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectLaunchTplTransformer;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectLaunchTplRequest;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class ProjectLaunchTplController extends Controller
{

    public function index(Request $request, ProjectLaunchTpl $projectLaunchTpl): Response
    {
        $query = $projectLaunchTpl->query();
        $table = $query->getModel()->getTable();

        $arUserZ = getArUserZ($this->user(), $request);
        handPointQuery($request, $query, $arUserZ, true);
        if ($request->filled('name')) {
            $query->where("$table.name", 'like', '%' . $request->get('name') . '%');
        }

        $projectLaunchTpl = $query->selectRaw($query->getModel()->getTable() . '.*')->orderBy('tvid', 'desc')->paginate(10);

        return $this->response()->paginator($projectLaunchTpl, new ProjectLaunchTplTransformer());

    }

    public function store(ProjectLaunchTplRequest $request, ProjectLaunchTpl $tpl)
    {
        $fillData = $this->convert($request->all());
        $tpl->fill($fillData)->save();
        return $this->response()->item($tpl, new ProjectLaunchTplTransformer())
            ->setStatusCode(201);
    }

    public function update(ProjectLaunchTplRequest $request, ProjectLaunchTpl $tpl)
    {
        $updateData = $this->convert($request->all());
        $tpl->update($updateData);
        return $this->response()->item($tpl, new ProjectLaunchTplTransformer())
            ->setStatusCode(201);
    }

    private function convert($input): array
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
