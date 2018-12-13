<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Coupon\V1\Transformer\PolicyTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['points', 'policy'];

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
            'versionname' => $project->versionname,
            'link' => $project->link,
            'created_at' => $project->date,
            'policy_id' => $project->policy_id,
            'updated_at' => formatClientDate($project->clientdate)
        ];
    }

    public function includePoints(Project $project)
    {
        return $this->collection($project->points, new PointTransformer());
    }

    public function includePolicy(Project $project)
    {
        $policy = $project->policy;
        if ($policy) {
            return $this->item($project->policy, new PolicyTransformer());
        }
    }

}