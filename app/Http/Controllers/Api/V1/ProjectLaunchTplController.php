<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\ProjectLaunchTpl;
use App\Transformers\ProjectLaunchTplTransformer;

class ProjectLaunchTplController extends Controller
{
    public function query(Request $request, ProjectLaunchTpl $projectLaunchTpl)
    {
        $query = $projectLaunchTpl->query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $templates = $query->where('oid', '=', 0)->get();

        return $this->response->collection($templates, new ProjectLaunchTplTransformer());

    }
}
