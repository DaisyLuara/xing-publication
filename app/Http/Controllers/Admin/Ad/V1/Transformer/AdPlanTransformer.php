<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use League\Fractal\TransformerAbstract;

class AdPlanTransformer extends TransformerAbstract
{
    public function transform(AdPlan $advertiser)
    {
        return [
            'id' => $advertiser->atiid,
            'adTrade' => $advertiser->adTrade->name,
            'name' => $advertiser->name,
            'icon' => $advertiser->icon,
            'created_at' => $advertiser->date,
            'updated_at' => formatClientDate($advertiser->clientdate),
        ];
    }
}