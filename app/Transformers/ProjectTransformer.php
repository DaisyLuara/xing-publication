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
            'alias' => $project->versionname,
            'version_code' => $project->versioncode,
            'link' => $project->link,
            'created_at' => $project->date,
            'updated_at' => formatClientDate($project->clientdate),
        ];
    }

    public function includePoints(Project $project)
    {
        return $this->collection($project->points, new PointTransformer());
    }

}