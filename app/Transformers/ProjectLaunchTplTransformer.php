<?php

namespace App\Transformers;

use App\Models\ProjectLaunchTpl;
use League\Fractal\TransformerAbstract;

class ProjectLaunchTplTransformer extends TransformerAbstract
{
    public function transform(ProjectLaunchTpl $projectLaunchTpl)
    {
        return [
            'id' => $projectLaunchTpl->tvid,
            'name' => $projectLaunchTpl->name,
        ];
    }

}