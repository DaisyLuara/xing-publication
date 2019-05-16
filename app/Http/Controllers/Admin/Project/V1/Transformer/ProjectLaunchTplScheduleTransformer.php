<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule;
use App\Http\Controllers\Admin\Skin\V1\Models\Skin;
use App\Http\Controllers\Admin\Skin\V1\Transformer\SkinTransformer;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class ProjectLaunchTplScheduleTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['project', 'skin'];

    public function transform(ProjectLaunchTplSchedule $projectLaunchTplSchedule): array
    {
        return [
            'id' => $projectLaunchTplSchedule->tviid,
            'tpl_id' => $projectLaunchTplSchedule->tvid,
            'date_start' => $this->formatTime($projectLaunchTplSchedule->shm),
            'date_end' => $this->formatTime($projectLaunchTplSchedule->ehm),
        ];
    }

    public function includeProject(ProjectLaunchTplSchedule $projectLaunchTplSchedule): ?Item
    {
        $project = $projectLaunchTplSchedule->project;
        if ($project) {
            return $this->item($projectLaunchTplSchedule->project, new ProjectTransformer());
        }
    }

    public function includeSkin(ProjectLaunchTplSchedule $projectLaunchTplSchedule): Item
    {
        return $this->item($projectLaunchTplSchedule->skin, new SkinTransformer());
    }

    private function formatTime($time)
    {
        $time = str_pad($time, 4, '0', STR_PAD_LEFT);
        return substr_replace($time, ':', 2, 0);
    }
}