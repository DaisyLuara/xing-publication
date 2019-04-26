<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Order\V1\Transformer;

use App\Http\Controllers\Admin\Order\V1\Models\OrderItem;
use App\Http\Controllers\Admin\Product\V1\Transformer\ProductTransformer;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Product\V1\Transformer\ProductSkuTransformer;

class OrderItemTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['productSku', 'product'];

    public function transform(OrderItem $orderItem)
    {
        return [
            'id' => $orderItem->id,
            'product_sku_id' => $orderItem->product_sku_id,
            'product_id' => $orderItem->product_id,
            'amount' => $orderItem->amount,
            'price' => $orderItem->price,
            'rating' => $orderItem->rating,
            'review' => $orderItem->review,
            'reviewed_at' => $orderItem->reviewed_at,
        ];
    }

    public function includeProductSku(OrderItem $orderItem): \League\Fractal\Resource\Item
    {
        return $this->item($orderItem->productSku, new ProductSkuTransformer());
    }

    public function includeProduct(OrderItem $orderItem): \League\Fractal\Resource\Item
    {
        return $this->item($orderItem->product, new ProductTransformer());
    }
}
