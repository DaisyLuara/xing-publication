<?php

namespace App\Transformers;


use App\Models\AdTrade;
use League\Fractal\TransformerAbstract;

class AdTradeTransformer extends TransformerAbstract
{
    public function transform(AdTrade $adTrade){
        return [
            'id'=>$adTrade->atid,
            'name'=>$adTrade->name,
        ];
    }
}