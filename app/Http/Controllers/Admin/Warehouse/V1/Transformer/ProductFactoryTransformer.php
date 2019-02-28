<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory;
use League\Fractal\TransformerAbstract;

class ProductFactoryTransformer extends TransformerAbstract
{
    public function transform(ProductFactory $productFactory)
    {
        $content = $productFactory->product_content;
        return [
            'contract_id' => $productFactory->contract_id,
            'product_content' => \GuzzleHttp\json_decode($content, true),
        ];
    }
}