<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;
use League\Fractal\TransformerAbstract;

class ProjectLaunchTplTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['schedules'];

    public function transform(ProjectLaunchTpl $projectLaunchTpl)
    {
        return [
            'id' => $projectLaunchTpl->tvid,
            'name' => $projectLaunchTpl->name,
            'point_name' => $projectLaunchTpl->point_name,
            'area_name' => $projectLaunchTpl->area_name,
            'market_name' => $projectLaunchTpl->market_name,
        ];
    }

    public function includeSchedules(ProjectLaunchTpl $projectLaunchTpl)
    {
        return $this->collection($projectLaunchTpl->schedules, new ProjectLaunchTplScheduleTransformer());
    }
}