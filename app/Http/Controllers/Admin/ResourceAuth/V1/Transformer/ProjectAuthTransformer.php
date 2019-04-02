<?php

namespace App\Http\Controllers\Admin\ResourceAuth\V1\Transformer;

use App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth;
use League\Fractal\TransformerAbstract;

class ProjectAuthTransformer extends TransformerAbstract
{
    public function transform(ProjectAuth $projectAuth)
    {
        return [
            'id' => $projectAuth->id,
            'customer_id' => $projectAuth->customer ? $projectAuth->customer->id : null,
            'customer_name' => $projectAuth->customer ? $projectAuth->customer->name : null,
            'project_id' => $projectAuth->pid,
            'project_name' => $projectAuth->project ? $projectAuth->project->name : null,
            'date' => (string)$projectAuth->date,
        ];
    }
}