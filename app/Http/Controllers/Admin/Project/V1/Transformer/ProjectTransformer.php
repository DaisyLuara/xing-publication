<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Coupon\V1\Transformer\PolicyTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['points', 'policy'];

    public function transform(Project $project)
    {
        $teamProject = TeamProject::where("belong", $project->versionname)->first();

        $member = [];
        if ($teamProject) {
            $member = $teamProject->member->toArray();
            $member = collect(array_column($member, 'pivot'))->groupBy("type")->toArray();
        }

        return [
            'id' => $project->id,
            'name' => $project->name,
            'info' => $project->info,
            'icon' => replaceDomain($project->icon),
            'image' => replaceDomain( $project->image),
            'alias' => $project->versionname,
            'version_code' => $project->versioncode,
            'versionname' => $project->versionname,
            'link' => replaceDomain($project->link),
            'created_at' => $project->date,
            'policy_id' => $project->policy_id,
            'updated_at' => formatClientDate($project->clientdate),
            'tester' => ($member && isset($member['tester_quality'])) ? implode(',', array_column($member['tester_quality'] ?? [], 'user_name')) : null,
            'operation' => ($member && isset($member['operation_quality'])) ? implode(',', array_column($member['operation_quality'] ?? [], 'user_name')) : null
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