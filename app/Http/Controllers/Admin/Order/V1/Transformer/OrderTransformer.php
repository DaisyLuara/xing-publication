<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Order\V1\Transformer;

use App\Http\Controllers\Admin\Cart\V1\Models\CartItem;
use App\Http\Controllers\Admin\Order\V1\Models\Order;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Product\V1\Transformer\ProductSkuTransformer;

class OrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['items'];

    public function transform(Order $order): array
    {
        return [
            'id' => $order->id,
            'no' => $order->no,
            'customer_id' => $order->customer_id,
            'address' => $order->address,
            'total_amount' => $order->total_amount,
            'remark' => $order->remark,
            'paid_at' => $order->paid_at,
            'payment_method' => $order->payment_method,
            'payment_no' => $order->payment_no,
            'refund_status' => $order->refund_status,
            'refund_no' => $order->refund_no,
            'reviewed' => $order->reviewed,
            'ship_status' => $order->ship_status,
            'ship_data' => $order->ship_data,
            'extra' => $order->extra,
            'created_at' => $order->created_at->toDatetimeString(),
            'updated_at' => $order->updated_at->toDatetimeString(),
        ];
    }

    public function includeItems(Order $order): \League\Fractal\Resource\Collection
    {
        return $this->collection($order->items, new OrderItemTransformer());
    }
}
