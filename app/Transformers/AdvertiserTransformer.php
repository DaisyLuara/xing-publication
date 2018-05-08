<?php

namespace App\Transformers;


use App\Models\Advertiser;
use League\Fractal\TransformerAbstract;

class AdvertiserTransformer extends TransformerAbstract
{
    public function transform(Advertiser $advertiser){
        return [
            'id'=>$advertiser->atiid,
            'name'=>$advertiser->name,
        ];
    }
}