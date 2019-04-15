<?php

namespace App\Http\Controllers\Admin\Order\V1\Api;

use App\Http\Controllers\Admin\Order\V1\Transformer\OrderTransformer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Order\V1\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        return $this->response->item($order, new OrderTransformer());
    }

    public function index()
    {
        $orders = Order::query()->paginate(10);
        return $this->response->paginator($orders, new OrderTransformer());
    }

    public function ship(Order $order, Request $request)
    {

        // 判断当前订单是否已支付
        if (!$order->paid_at) {
            abort(500, '该订单未付款');
        }
        // 判断当前订单发货状态是否为未发货
        if ($order->ship_status !== Order::SHIP_STATUS_PENDING) {
            abort(500, '该订单已发货');
        }
        // Laravel 5.5 之后 validate 方法可以返回校验过的值
        $data = $this->validate($request, [
            'express_company' => ['required'],
            'express_no' => ['required'],
        ], [], [
            'express_company' => '物流公司',
            'express_no' => '物流单号',
        ]);
        // 将订单发货状态改为已发货，并存入物流信息
        $order->update([
            'ship_status' => Order::SHIP_STATUS_DELIVERED,
            // 我们在 Order 模型的 $casts 属性里指明了 ship_data 是一个数组
            // 因此这里可以直接把数组传过去
            'ship_data' => $data,
        ]);

    }
}
