<?php

namespace App\Transformers;


use App\Models\ProjectAdLog;
use League\Fractal\TransformerAbstract;

class ProjectAdLogTransformer extends TransformerAbstract
{
    public function transform(ProjectAdLog $adLog)
    {
        return [
            'area_name' => $adLog->areaName,
            'market_name' => $adLog->marketName,
            'point_name' => $adLog->pointName,
            'project_name' => $adLog->projectName,
            'project_icon' => $adLog->projectIcon,
            'advertiser' => $adLog->advertiser,
            'ad_icon' => $adLog->ad_icon,
            'count' => $adLog->count,
        ];
    }
}