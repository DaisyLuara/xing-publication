<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Product\V1\Transformer;

use App\Http\Controllers\Admin\Product\V1\Models\ProductSku;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['product'];

    public function transform(ProductSku $productSku): array
    {
        return [
            'id' => $productSku->id,
            'title' => $productSku->title,
            'description' => $productSku->description,
            'stock' => $productSku->stock,
        ];
    }

    public function includeProduct(ProductSku $productSku): \League\Fractal\Resource\Item
    {
        return $this->item($productSku->product, new ProductTransformer());
    }
}
