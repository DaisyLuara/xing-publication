<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule;
use League\Fractal\TransformerAbstract;

class ProjectLaunchTplScheduleTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['project'];

    public function transform(ProjectLaunchTplSchedule $projectLaunchTplSchedule)
    {
        return [
            'id' => $projectLaunchTplSchedule->tvid,
            'date_start' => $projectLaunchTplSchedule->shm,
            'date_end' => $projectLaunchTplSchedule->ehm,
        ];
    }

    public function includeProject(ProjectLaunchTplSchedule $projectLaunchTplSchedule)
    {
        return $this->item($projectLaunchTplSchedule->project, new ProjectTransformer());
    }
}