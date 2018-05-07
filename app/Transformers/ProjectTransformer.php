<?php

namespace App\Transformers;

use App\Models\Project;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['points'];

    public function transform(Project $project)
    {
        return [

            'id' => $project->id,
            'name' => $project->name,
            'info' => $project->info,
            'icon' => $project->icon,
            'image' => $project->image,
            'versionname'=>$project->versionname,
        ];
    }

    public function includePoints(Project $project)
    {
        return $this->collection($project->points, new PointTransformer());
    }

}