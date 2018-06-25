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
            'area' => ['name' => $projectLaunchTpl->area_name, 'id' => $projectLaunchTpl->area_id],
            'market' => ['name' => $projectLaunchTpl->market_name, 'id' => $projectLaunchTpl->market_id],
            'point' => ['name' => $projectLaunchTpl->point_name, 'id' => $projectLaunchTpl->point_id],
        ];
    }

    public function includeSchedules(ProjectLaunchTpl $projectLaunchTpl)
    {
        return $this->collection($projectLaunchTpl->schedules, new ProjectLaunchTplScheduleTransformer());
    }
}