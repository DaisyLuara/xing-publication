<?php

namespace App\Http\Controllers\Admin\Order\V1\Api;

use App\Http\Controllers\Admin\Order\V1\Transformer\OrderTransformer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Order\V1\Models\Order;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::query()->paginate(10);
        return $this->response->paginator($orders, new OrderTransformer());
    }
}
