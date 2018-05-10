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
            'created_at' => date('Y-m-d H:i:s', $project->clientdate / 1000),
            'version_code' => $project->versioncode,
            'link' => $project->link,
        ];
    }

    public function includePoints(Project $project)
    {
        return $this->collection($project->points, new PointTransformer());
    }

}