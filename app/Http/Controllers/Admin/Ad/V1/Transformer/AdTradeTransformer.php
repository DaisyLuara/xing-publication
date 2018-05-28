<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Models\AdTrade;
use League\Fractal\TransformerAbstract;

class AdTradeTransformer extends TransformerAbstract
{
    public function transform(AdTrade $adTrade)
    {
        return [
            'id' => $adTrade->atid,
            'name' => $adTrade->name,
        ];
    }
}