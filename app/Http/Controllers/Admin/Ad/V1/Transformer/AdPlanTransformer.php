<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class AdPlanTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['advertisements'];

    public function transform(AdPlan $adPlan): array
    {
        return [
            'id' => $adPlan->atiid,
            'aids' => $adPlan->advertisements->pluck('aid')->toArray(),
            'ad_trade' => $adPlan->ad_trade->name,
            'name' => $adPlan->name,
            'icon' => $adPlan->icon,
            'type' => $adPlan->type,
            'type_text' => AdPlan::$typeMapping[$adPlan->type] ?? '未知',
            'created_at' => $adPlan->date,
            'updated_at' => formatClientDate($adPlan->clientdate),
        ];
    }

    public function includeAdvertisements(AdPlan $adPlan): Collection
    {
        $advertisements = $adPlan->advertisements;

        return $this->collection($advertisements, new AdvertisementTransformer());
    }
}