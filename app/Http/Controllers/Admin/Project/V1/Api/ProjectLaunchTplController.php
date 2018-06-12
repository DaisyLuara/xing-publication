<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectLaunchTplTransformer;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectLaunchTplController extends Controller
{

    public function index(Request $request,ProjectLaunchTpl $projectLaunchTpl)
    {
        $query = $projectLaunchTpl->query();
        if ($request->project_id) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('id', '=', $request->project_id);
            });
        }

        $projectLaunchTpl = $query->paginate(10);

        return $this->response->paginator($projectLaunchTpl, new ProjectLaunchTplTransformer());

    }
}
