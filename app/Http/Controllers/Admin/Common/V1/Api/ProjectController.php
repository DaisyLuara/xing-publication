<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show(Request $request)
    {
        $project = Project::query()->where('versionname', '=', $request->belong)->firstOrFail();
        return $this->response->item($project, new ProjectTransformer());
    }

}
