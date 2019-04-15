<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Product\V1\Transformer;

use App\Http\Controllers\Admin\Product\V1\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{

    public function transform(Product $product): array
    {
        return [
            'id' => $product->id,
            'title' => $product->id,
            'description' => $product->description,
            'image' => $product->image,
            'on_sale' => $product->on_sale,
            'rating' => $product->rating,
            'sold_count' => $product->sold_count,
            'review_count' => $product->review_count,
            'price' => $product->price,
        ];
    }
}
