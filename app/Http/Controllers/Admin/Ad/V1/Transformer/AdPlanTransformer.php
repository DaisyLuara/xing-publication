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
            'create_user_name' => ($adPlan->create_customer ? $adPlan->create_customer->name : null)
                ?? ($adPlan->create_user ? $adPlan->create_user->name : ''),
            'create_user_company' => ($adPlan->create_customer && $adPlan->create_customer->company) ? $adPlan->create_customer->company->internal_name : '',
            'ad_trade_name' => $adPlan->ad_trade->name,
            'atid' => $adPlan->atid,
            'name' => $adPlan->name,
            'info' => $adPlan->info,
            'icon' => $adPlan->icon,
            'type' => $adPlan->type,
            'type_text' => AdPlan::$typeMapping[$adPlan->type] ?? '未知',
            'hardware' => $adPlan->hardware,
            'tmode' => $adPlan->tmode,
            'tmode_text' => AdPlan::$tmodeMapping[$adPlan->tmode] ?? '未知',
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