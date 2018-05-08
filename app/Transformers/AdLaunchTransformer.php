<?php

namespace App\Transformers;


use App\Models\AdLaunch;
use League\Fractal\TransformerAbstract;

class AdLaunchTransformer extends TransformerAbstract
{
    public function transform(AdLaunch $adLaunch)
    {
        return [
            'id' => $adLaunch->aoid,
            'point' => $adLaunch->area->name.'-'.$adLaunch->market->name.'-'.$adLaunch,
            'advertiser' => $adLaunch->advertiser->name,
            'advertisement' => $adLaunch->advertisement->name,
            'adType' => $adLaunch->advertisement->type,
            'link'=>$adLaunch->advertisement->link,
            'size'=>($adLaunch->advertisement->size)/1024/1024,
            'kTime'=>$adLaunch->ktime,
            'startDate'=>date('Y-m-d H:i:s',$adLaunch->sdate),
            'endDate'=>date('Y-m-d H:i:s',$adLaunch->edate),
            'date'=>$adLaunch->date,
        ];
    }
}