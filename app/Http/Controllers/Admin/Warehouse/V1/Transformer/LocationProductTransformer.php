<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse;
use League\Fractal\TransformerAbstract;

class LocationProductTransformer extends TransformerAbstract
{
    public function transform(LocationProduct $locationProduct)
    {
        $warehouseId = $locationProduct->location->warehouse_id;
        $warehouse = Warehouse::query()->where('id', $warehouseId)->select('name')->get()->toArray();

        return [
            'id' => $locationProduct->id,
            'sku' => $locationProduct->product_sku,
            'location' => $locationProduct->location->name,
            'warehouse' => $warehouse[0]['name'],
//            'product' => $locationProduct->product,
            'stock' => (int)$locationProduct->stock,
        ];
    }
}