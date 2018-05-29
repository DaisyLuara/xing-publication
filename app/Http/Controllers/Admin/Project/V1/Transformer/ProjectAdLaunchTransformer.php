<?php

namespace App\Http\Controllers\Admin\Project\V1\Transformer;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch;
use League\Fractal\TransformerAbstract;

class ProjectAdLaunchTransformer extends TransformerAbstract
{
    public function transform(ProjectAdLaunch $projectAdLaunch)
    {
        return [
            'id' => $projectAdLaunch->adid,
            'name' => $projectAdLaunch->project->name,
            'icon' => $projectAdLaunch->project->icon,
            'type' => $projectAdLaunch->type,
            'adName' => $projectAdLaunch->wxThird->nick_name,
            'ad' => $projectAdLaunch->wxThird->head_img,
            'created_at' => $projectAdLaunch->date,
            'updated_at' => formatClientDate($projectAdLaunch->clientdate),
        ];
    }
}