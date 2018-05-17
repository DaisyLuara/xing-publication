<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/17
 * Time: 19:59
 */

namespace App\Http\Controllers\Api\V1;


use App\Models\ProjectTemplate;
use App\Transformers\ProjectTemplateTransformer;
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