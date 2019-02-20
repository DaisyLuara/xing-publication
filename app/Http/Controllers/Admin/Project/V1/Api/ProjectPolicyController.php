<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;

use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectTransformer;
use App\Http\Controllers\Admin\Project\V1\Request\ProjectPolicyRequest;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Controller;
use DB;

class ProjectPolicyController extends Controller
{
    public function store(ProjectPolicyRequest $projectPolicyRequest, Project $project, Policy $policy)
    {
        $policy = $policy->query()->findOrFail($projectPolicyRequest->policy_id);

        foreach ($projectPolicyRequest->oids as $oid) {
            $projectPolicy = $policy->projects()->where('ar_product_list.id', $project->id)->wherePivot('oid', $oid)->first();

            if (!$projectPolicy) {
                $policy->projects()->attach($project, ['oid' => $oid]);
            }
        }

        return $this->response->item($project, new ProjectTransformer())
            ->setStatusCode(201);
    }

    public function update(ProjectPolicyRequest $projectPolicyRequest, Project $project, Policy $policy)
    {
        $policy->projects()->detach();

        foreach ($projectPolicyRequest->oids as $oid) {
            $policy->projects()->attach($project, ['oid' => $oid]);
        }

        return $this->response->item($project, new ProjectTransformer())
            ->setStatusCode(201);
    }

    public function destroy(Project $project, Policy $policy)
    {
        $policy->projects()->detach();

        return $this->response->item($project, new ProjectTransformer())
            ->setStatusCode(201);

    }
}
