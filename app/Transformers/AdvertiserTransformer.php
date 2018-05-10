<?php

namespace App\Transformers;


use App\Models\Advertiser;
use League\Fractal\TransformerAbstract;

class AdvertiserTransformer extends TransformerAbstract
{
    public function transform(Advertiser $advertiser){
        return [
            'id'=>$advertiser->atiid,
            'adTrade'=>$advertiser->adTrade->name,
            'name'=>$advertiser->name,
            'icon'=>$advertiser->icon,
            'date'=>$advertiser->date,
        ];
    }
}