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



}
