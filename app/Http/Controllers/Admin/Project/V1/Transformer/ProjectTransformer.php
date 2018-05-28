<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
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