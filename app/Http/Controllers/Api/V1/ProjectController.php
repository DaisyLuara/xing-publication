<?php

namespace App\Http\Controllers\Api\V1;

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
}
