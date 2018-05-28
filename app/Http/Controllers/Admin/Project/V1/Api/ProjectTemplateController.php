<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Models\ProjectTemplate;
use App\Transformers\ProjectTemplateTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectTemplateController extends Controller
{
    public function index(Request $request, ProjectTemplate $projectTemplate)
    {
        $query = $projectTemplate->query();
        if ($request->type) {
            $query->where('type', '=', $request->type);
        }
        $projectTemplate = $query->where('tid', '<>', '0')
            ->orderBy('date', 'desc')
            ->paginate(10);
        return $this->response->paginator($projectTemplate, new ProjectTemplateTransformer());
    }
}