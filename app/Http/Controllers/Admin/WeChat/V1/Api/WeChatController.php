<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Api;

use App\Http\Controllers\Admin\WeChat\V1\Models\WxThird;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use EasyWeChat;

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

    public function preAuthUrl(Request $request)
    {
        $redirectUrl = $request->redirect_url;
        $openPlatform = EasyWeChat::openPlatform();
        $preAuthUrl = $openPlatform->getPreAuthorizationUrl('http://47.98.106.247/api/openPlatform/authorization?redirect_url=' . $redirectUrl);
        return "<!DOCTYPE HTML><html lang=\"en-US\"><head>  <meta http-equiv=\"refresh\" content=\"0; url=$preAuthUrl\">        <script type=\"text/javascript\">            window.location.href = \"$preAuthUrl\"        </script>        <title>Page Redirection</title>    </head>    <body>        <!-- Note: don't tell people to `click` the link, just tell them that it is a link. -->        If you are not redirected automatically, follow this <a href='$preAuthUrl'>link to wechat</a> </body></html>";
    }

    public function authorization(Request $request)
    {
        /** @var \EasyWeChat\OpenPlatform\Application $openPlatform */
        $openPlatform = EasyWeChat::openPlatform();
        $authorization = $openPlatform->handleAuthorize();
        $authorizer = $openPlatform->getAuthorizer($authorization['authorization_info']['authorizer_appid']);
        dd($authorizer);
        $data = [
            'appid' => $authorizer['authorization_info']['authorizer_appid'],
            'expires_in' => 7200,
            'access_token' => $authorizer['authorization_info']['authorizer_access_token'],
            'refresh_token' => $authorizer['authorization_info']['authorizer_refresh_token'],
            'nick_name' => $authorizer['authorizer_info']['nick_name'],
            'user_name' => $authorizer['authorizer_info']['user_name'],
            'head_img' => $authorizer['authorizer_info']['head_img'],
            'qrcode_url' => $authorizer['authorizer_info']['qrcode_url'],
            'url' => $authorizer['authorizer_info']['url'],
            'service_type' => $authorizer['authorizer_info']['service_type_info']['id'],
            'verify_type' => $authorizer['authorizer_info']['verify_type_info']['id']
        ];
        WxThird::create(array_merge(['date' => date('Y-m-d H:i:s'), 'clientdate' => time() * 1000], $data));

        return response()->redirectTo($request->redirect_url);
    }

    public function events(Request $request)
    {
        $appid = $request->appid;
        $wxThird = WxThird::where('appid', '=', $appid)->first();
        if (empty($wxThird)) {
            return response()->json(['message' => 'official account not authorization']);
        }
        $openPlatform = EasyWeChat::openPlatform();
        $officialAccount = $openPlatform->officialAccount($appid, $wxThird->refresh_token);
        $response = $officialAccount->server->serve();
        return $response;
    }

}