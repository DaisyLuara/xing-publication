<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/21
 * Time: 11:36
 */

namespace App\Http\Controllers\Api\V1;


use EasyWeChat;
use Illuminate\Http\Request;

class WeChatController extends Controller
{

//    public function oauth(Request $request)
//    {
//        $officialAccount = EasyWeChat::officialAccount();
//        $redirect_url = $officialAccount->oauth->getRedirectUrl();
//        return $officialAccount->oauth->scopes(['snsapi_userinfo'])->setRequest($request)->redirect($redirect_url);
//
//    }
//
//    public function callback(Request $request)
//    {
//        $officialAccount = EasyWeChat::officialAccount();
//        $user = $officialAccount->oauth->user();
//
//        $accessToken = $user->token['access_token'];
//        $refreshToken = $user->token['refresh_token'];
//        $openId = $user->token['openid'];
//        dd($accessToken, $refreshToken, $openId);
//    }
//
//    public function message(Request $request)
//    {
//        /** @var  \EasyWeChat\OpenPlatform\Application $officialAccount */
//        $officialAccount = EasyWeChat::officialAccount();
//        $text = new Text('test');
//        return $officialAccount->customer_service->message($text)->to('ott1x1V0a7b_KKH2XnLs_-YISIso')->send();
//    }
//
//    public function menu()
//    {
//        $officialAccount = EasyWeChat::officialAccount();
//
//        $button = [
//            [
//                'type' => 'click',
//                'name' => 'test1',
//                'key' => 'V0521_Test1',
//            ]
//        ];
//        //return $officialAccount->menu->create($button);
//        return $officialAccount->menu->delete();
//    }
//
//    public function qrCode()
//    {
//        $payment = EasyWeChat::payment();
//        return $payment->order->unify([
//            'body' => '下单测试',
//            'out_trade_no' => '20150806125346',
//            'total_fee' => 1,
//            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
//            'trade_type' => 'JSAPI',
//        ]);
//    }


// ----------------------------------open_platform---------------------
    public function serve()
    {
        $openPlatform = EasyWeChat::openPlatform();
        return $openPlatform->server->serve();
    }

    public function authorization(Request $request)
    {
        return $this->response()->array('test');
    }

    public function events(Request $request)
    {

        $openPlatform = EasyWeChat::openPlatform();
        return $openPlatform->server->serve();
    }

}