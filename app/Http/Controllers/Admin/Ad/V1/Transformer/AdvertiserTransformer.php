<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Models\Advertiser;
use League\Fractal\TransformerAbstract;

class AdvertiserTransformer extends TransformerAbstract
{
    public function transform(Advertiser $advertiser)
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