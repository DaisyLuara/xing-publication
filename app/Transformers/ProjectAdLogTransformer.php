<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/17
 * Time: 14:25
 */

namespace App\Transformers;


use App\Models\ProjectAdLog;
use League\Fractal\TransformerAbstract;

class ProjectAdLogTransformer extends TransformerAbstract
{
    public function transform(ProjectAdLog $adLog)
    {
        return [
            'area' => $adLog->areaName,
            'market' => $adLog->marketName,
            'point' => $adLog->pointName,
            'project' => $adLog->projectName,
            'project_icon' => $adLog->projectIcon,
            'advertiser' => $adLog->advertiser,
            'ad_icon' => $adLog->ad_icon,
            'count' => $adLog->count,
        ];
    }
}