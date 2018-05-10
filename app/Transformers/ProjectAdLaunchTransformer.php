<?php

namespace App\Transformers;


use App\Models\ProjectAdLaunch;
use League\Fractal\TransformerAbstract;

class ProjectAdLaunchTransformer extends TransformerAbstract
{
    public function transform(ProjectAdLaunch $projectAdLaunch){
        return [
            'id'=>$projectAdLaunch->adid,
            'name'=>$projectAdLaunch->project->name,
            'icon'=>$projectAdLaunch->project->icon,
            'type'=>$projectAdLaunch->type,
            'adName'=>$projectAdLaunch->wxThird->nick_name,
            'ad'=>$projectAdLaunch->wxThird->head_img,
            'date'=>$projectAdLaunch->date,
        ];
    }
}