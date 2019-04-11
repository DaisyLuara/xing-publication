<?php

namespace App\Http\Controllers\Admin\Product\V1\Transformer;

use App\Http\Controllers\Product\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{

    public function transform(Product $product)
    {
        return [
            'id' => (int)$product->id,
            'name' => $product->name,
        ];
    }
}