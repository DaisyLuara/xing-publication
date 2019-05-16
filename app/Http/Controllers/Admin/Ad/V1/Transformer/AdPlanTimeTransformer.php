<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class AdPlanTimeTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['ad_plan'];

    public function transform(AdPlanTime $adPlanTime): array
    {
        $shm = str_pad($adPlanTime->shm,4,'0',STR_PAD_LEFT);
        $ehm = str_pad($adPlanTime->ehm,4,'0',STR_PAD_LEFT);

        return [
            'id' => $adPlanTime->id,
            'atiid' => $adPlanTime->atiid,
            'aid' => $adPlanTime->aid,
            'mode' => $adPlanTime->mode,
            'ori' => $adPlanTime->ori,
            'screen' => $adPlanTime->screen,
            'cdshow' => $adPlanTime->cdshow,
            'shm' => substr($shm,0,2).':'.substr($shm,2,2),
            'ehm' => substr($shm,0,2).':'.substr($ehm,2,2),
            'ktime' => $adPlanTime->ktime,
            'only' => $adPlanTime->only,
        ];
    }

    public function includeAdPlan(AdPlanTime $adPlanTime): Collection
    {
        $ad_plan = $adPlanTime->ad_plan;

        return $this->collection($ad_plan, new AdPlanTransformer());
    }
}