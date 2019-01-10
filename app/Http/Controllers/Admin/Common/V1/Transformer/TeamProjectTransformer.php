<?php

namespace App\Http\Controllers\Admin\Common\V1\Transformer;

use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use League\Fractal\TransformerAbstract;

class TeamProjectTransformer extends TransformerAbstract
{


    public function transform(TeamProject $teamProject)
    {

        return [
            'id' => $teamProject->id,
            'project_name' => $teamProject->project_name,
            'belong' => $teamProject->belong,
        ];
    }

}