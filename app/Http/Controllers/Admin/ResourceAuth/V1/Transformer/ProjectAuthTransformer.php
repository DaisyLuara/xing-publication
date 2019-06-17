<?php

namespace App\Http\Controllers\Admin\ResourceAuth\V1\Transformer;

use App\Http\Controllers\Admin\Common\V1\Transformer\CustomerTransformer;
use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectTransformer;
use App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth;
use App\Http\Controllers\Admin\Skin\V1\Transformer\SkinTransformer;
use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class ProjectAuthTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['customer', 'project', 'skin'];

    public function transform(ProjectAuth $projectAuth): array
    {
        return [
            'id' => $projectAuth->id,
            'customer_id' => $projectAuth->customer ? $projectAuth->customer->id : null,
            'customer_name' => $projectAuth->customer ? $projectAuth->customer->name : null,
            'project_id' => $projectAuth->pid,
            'skin_id' => $projectAuth->bid,
            'project_name' => $projectAuth->project ? $projectAuth->project->name : null,
            'skin_name' => $projectAuth->skin ? $projectAuth->skin->name : null,
            'date' => (string)$projectAuth->date,
        ];
    }

    public function includeCustomer(ProjectAuth $projectAuth)
    {
        $customer = $projectAuth->customer;
        if ($customer) {
            return $this->item($customer, new CustomerTransformer());
        }
        return null;
    }

    public function includeProject(ProjectAuth $projectAuth)
    {
        $project = $projectAuth->project;
        if ($project) {
            return $this->item($project, new ProjectTransformer());
        }
        return null;
    }

    public function includeSkin(ProjectAuth $projectAuth)
    {
        $skin = $projectAuth->skin;
        if ($skin) {
            return $this->item($skin, new SkinTransformer());
        }
        return null;
    }

}