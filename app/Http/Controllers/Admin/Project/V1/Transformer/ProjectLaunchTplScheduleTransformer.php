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
            'date_start' => $this->formatTime($projectLaunchTplSchedule->shm),
            'date_end' => $this->formatTime($projectLaunchTplSchedule->ehm),
        ];
    }

    public function includeProject(ProjectLaunchTplSchedule $projectLaunchTplSchedule)
    {
        return $this->item($projectLaunchTplSchedule->project, new ProjectTransformer());
    }

    private function formatTime($time)
    {
        $time = str_pad($time, 4, '0', STR_PAD_LEFT);
        return substr_replace($time, ':', 2, 0);
    }
}