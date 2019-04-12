<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse;
use League\Fractal\TransformerAbstract;

class LocationProductTransformer extends TransformerAbstract
{
    public function transform(LocationProduct $locationProduct)
    {
        return [
            'id' => $locationProduct->id,
            'sku' => isset($locationProduct->product->sku)?$locationProduct->product->sku:'',
            'location' => isset($locationProduct->location->name)?$locationProduct->location->name:'',
            'warehouse' => ($locationProduct->location && $locationProduct->location->warehouse) ? $locationProduct->location->warehouse->name : '',
            'stock' => (int)$locationProduct->stock,
        ];
    }
}