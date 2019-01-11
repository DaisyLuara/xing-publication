<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductChuchang;
use League\Fractal\TransformerAbstract;

class ProductChuchangTransformer extends TransformerAbstract
{
    public function transform(ProductChuchang $productChuchang)
    {
        $content = $productChuchang->product_content;
        return [
            'contract_id' => $productChuchang->contract_id,
            'product_content' => \GuzzleHttp\json_decode($content,true),
        ];
    }
}