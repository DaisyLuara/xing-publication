<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectTemplate;
use League\Fractal\TransformerAbstract;

class ProjectTemplateTransformer extends TransformerAbstract
{
    public function transform(ProjectTemplate $projectTemplate)
    {
        return [
            'id' => $projectTemplate->tid,
            'name' => $projectTemplate->name,
            'icon' => replaceDomain($projectTemplate->icon),
            'type' => $projectTemplate->type,
            'date' => $projectTemplate->date,
        ];
    }
}