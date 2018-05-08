<?php

namespace App\Transformers;

use App\Models\Market;
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