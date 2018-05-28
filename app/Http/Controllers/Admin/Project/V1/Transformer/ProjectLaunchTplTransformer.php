<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;
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