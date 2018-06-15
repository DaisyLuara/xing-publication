<?php

namespace App\Http\Controllers\Admin\Pay\V1\Api;

use App\Http\Controllers\Controller;
use Log;
use Yansongda\LaravelPay\Facades\Pay;

class PayController extends Controller
{
    public function aliPay()
    {
        $order = [
            'out_trade_no' => time(),
            'total_amount' => 1,
            'subject' => 'test subject'
        ];

        return Pay::alipay()->web($order);
    }

    public function notify()
    {
        $alipay = Pay::alipay();
        try {
            $data = $alipay->verify();
            Log::debug('Alipay notify', $data->all());
        } catch (Exception $e) {

        }
        return $alipay->success();
    }

    public function return()
    {
        $data = Pay::alipay()->verify();
//        $outTradeNo = $data->out_trade_no;    //商户订单号
//        $tradeNo = $data->trade_no;   //订单号
//        $totalAmount = $data->total_amount;
        Log::info($data);
    }

}
