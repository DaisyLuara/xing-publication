<?php

namespace App\Http\Controllers\Admin\Coupon\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch;
use League\Fractal\TransformerAbstract;

class PolicyLaunchTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['point', 'project', 'policy', 'company'];

    public function transform(PolicyLaunch $policyLaunch)
    {
        return [
            'id' => $policyLaunch->id,
            'created_at' => $policyLaunch->created_at->toDateTimeString(),
            'updated_at' => $policyLaunch->updated_at->toDateTimeString(),
        ];

    }

    public function includePoint(PolicyLaunch $policyLaunch)
    {
        return $this->item($policyLaunch->point, new PointTransformer());
    }

    public function includeProject(PolicyLaunch $policyLaunch)
    {
        return $this->item($policyLaunch->project, new ProjectTransformer());
    }

    public function includePolicy(PolicyLaunch $policyLaunch)
    {
        return $this->item($policyLaunch->policy, new PolicyTransformer());
    }

    public function includeCompany(PolicyLaunch $policyLaunch)
    {
        return $this->item($policyLaunch->company, new CompanyTransformer());
    }

}