<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use League\Fractal\TransformerAbstract;

class MarketTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['area'];

    public function transform(Market $market)
    {
        return [
            'id' => (int)$market->marketid,
            'name' => (string)$market->name,
        ];
    }

    public function includeArea(Market $market)
    {
        return $this->item($market->area, new AreaTransformer());
    }
}