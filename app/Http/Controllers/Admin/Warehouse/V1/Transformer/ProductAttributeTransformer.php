<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute;
use League\Fractal\TransformerAbstract;

class ProductAttributeTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['attributes'];

    public function transform(ProductAttribute $productAttribute)
    {
        return [
            'id' => $productAttribute->id,
            'product_id' => $productAttribute->product_id, //产品ID
            'attributes_id' => $productAttribute->attributes_id,
            'attributes_name' => $productAttribute->attribute->display_name,
            'attributes_value' => $productAttribute->attributes_value,
        ];
    }

    public function includeAttributes(ProductAttribute $productAttribute)
    {
        return $this->collection($productAttribute->attributes, new ProductAttributeTransformer());
    }

}