<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch;
use League\Fractal\TransformerAbstract;

class AdLaunchTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['ad_plan'];

    public function transform(AdLaunch $adLaunch): array
    {
        return [
            'id' => $adLaunch->aoid,
            'point' => ($adLaunch->market ? $adLaunch->market->name : '未知商场') . ($adLaunch->point ? $adLaunch->point->name : '未知点位'),//商场&点位
            'project' => $adLaunch->project ? $adLaunch->project->name : '',//节目
            'ad_plan' => $adLaunch->ad_plan ? $adLaunch->ad_plan->name : '未知广告方案',//广告方案
            'ad_trade' => $adLaunch->ad_plan->ad_trade ? $adLaunch->ad_plan->ad_trade->name : '未知广告行业',//广告行业
//            'advertisements' => $adLaunch->ad_plan->advertisements, //广告素材s
            'sdate' => date('Y-m-d H:i:s', $adLaunch->sdate),
            'edate' => date('Y-m-d H:i:s', $adLaunch->edate),
            'visiable' => $adLaunch->visiable === 1 ? '营业中' : '下架',
            'created_at' => $adLaunch->date,
            'updated_at' => formatClientDate($adLaunch->clientdate),
        ];

    }

    public function includeAdPlan(AdLaunch $adLaunch)
    {
        $ad_plan = $adLaunch->ad_plan;
        if ($ad_plan) {
            return $this->item($ad_plan, new AdPlanTransformer());
        }
        return null;
    }

}