<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch;
use League\Fractal\TransformerAbstract;

class AdLaunchTransformer extends TransformerAbstract
{
    public function transform(AdLaunch $adLaunch)
    {
        return [
            'id' => $adLaunch->aoid,
            'point' => $adLaunch->area->name . '-' . $adLaunch->market->name . $adLaunch->point->name,
            'advertiser' => $adLaunch->advertiser->name,
            'advertisement' => $adLaunch->advertisement->name,
            'adType' => $adLaunch->advertisement->type,
            'link' => $adLaunch->advertisement->link,
            'size' => round(($adLaunch->advertisement->size) / 1024 / 1024, 1) . 'M',
            'kTime' => (int)$adLaunch->ktime,
            'startDate' => date('Y-m-d H:i:s', $adLaunch->sdate),
            'endDate' => date('Y-m-d H:i:s', $adLaunch->edate),
            'created_at' => $adLaunch->date,
            'updated_at' => formatClientDate($adLaunch->clientdate),
        ];
    }
}