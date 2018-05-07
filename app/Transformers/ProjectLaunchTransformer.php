<?php

namespace App\Transformers;

use App\Models\Project;
use App\Models\ProjectLaunch;
use League\Fractal\TransformerAbstract;

class ProjectLaunchTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['point', 'project'];

    public function transform(ProjectLaunch $projectLaunch)
    {
        return [
            'id' => $projectLaunch->tvoid,
            'start_date' => date('Y-m-d H:i:s', $projectLaunch->sdate),
            'end_date' => date('Y-m-d H:i:s', $projectLaunch->edate),
            'created_at' => $projectLaunch->date,
        ];
    }

    public function includePoint(ProjectLaunch $projectLaunch)
    {
        return $this->item($projectLaunch->point, new PointTransformer());
    }

    public function includeProject(ProjectLaunch $projectLaunch)
    {
        return $this->item($projectLaunch->project, new ProjectTransformer());
    }

}