<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/17
 * Time: 20:03
 */

namespace App\Transformers;


use App\Models\ProjectTemplate;
use League\Fractal\TransformerAbstract;

class ProjectTemplateTransformer extends TransformerAbstract
{
    public function transform(ProjectTemplate $projectTemplate)
    {
        return [
            'id' => $projectTemplate->tid,
            'name' => $projectTemplate->name,
            'icon' => $projectTemplate->icon,
            'type' => $projectTemplate->type,
            'date' => $projectTemplate->date,
        ];
    }
}