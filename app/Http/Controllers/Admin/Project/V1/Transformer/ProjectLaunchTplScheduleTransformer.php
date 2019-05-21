<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule;
use App\Http\Controllers\Admin\Skin\V1\Models\Skin;
use App\Http\Controllers\Admin\Skin\V1\Transformer\SkinTransformer;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class ProjectLaunchTplScheduleTransformer extends TransformerAbstract
{
    public function transform(ProjectLaunchTplSchedule $projectLaunchTplSchedule): array
    {
        $project = $projectLaunchTplSchedule->project;
        $skin = $projectLaunchTplSchedule->skin;
        return [
            'id' => $projectLaunchTplSchedule->tviid,
            'tpl_id' => $projectLaunchTplSchedule->tvid,
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'icon' => $project->icon,
                'versionname' => $project->versionname
            ],
            'skin' => [
                'bid' => $skin->bid,
                'name' => $skin->name,
                'icon' => $skin->icon

            ],
            'date_start' => $this->formatTime($projectLaunchTplSchedule->shm),
            'date_end' => $this->formatTime($projectLaunchTplSchedule->ehm),
        ];
    }

    private function formatTime($time)
    {
        $time = str_pad($time, 4, '0', STR_PAD_LEFT);
        return substr_replace($time, ':', 2, 0);
    }
}