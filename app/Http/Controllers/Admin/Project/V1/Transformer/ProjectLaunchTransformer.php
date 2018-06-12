<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch;
use League\Fractal\TransformerAbstract;

class ProjectLaunchTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['point', 'project', 'template'];

    public function transform(ProjectLaunch $projectLaunch)
    {
        return [
            'id' => $projectLaunch->tvoid,
            'start_date' => date('Y-m-d H:i:s', $projectLaunch->sdate),
            'end_date' => date('Y-m-d H:i:s', $projectLaunch->edate),
            'created_at' => $projectLaunch->date,
            'updated_at' => formatClientDate($projectLaunch->clientdate),
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

    public function includeTemplate(ProjectLaunch $projectLaunch)
    {
        if ($projectLaunch->template) {
            return $this->item($projectLaunch->template, new ProjectLaunchTplTransformer());
        }
    }

}