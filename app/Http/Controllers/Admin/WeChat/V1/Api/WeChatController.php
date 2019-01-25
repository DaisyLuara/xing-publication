<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Api;

use App\Http\Controllers\Admin\WeChat\V1\Models\WxThird;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use EasyWeChat;

class WeChatController extends Controller
{

    public function redPack(){
        //已经发送过红包
        $payment = EasyWeChat::payment();
        $redpack = $payment->redpack;

        $mchBillno = date('YmdHis') . uniqid();
        $redpackData = [
            'mch_billno' => $mchBillno,
            'send_name' => '测试',
            're_openid' => $wxUser->openid,
            'total_num' => 1,
            'total_amount' => 100 * $couponBatch->amount,
            'wishing' => '新年快乐!',
            'act_name' => '刮卡抽奖！',
            'remark' => '刮卡抽奖',
            'scene_id' => 'PRODUCT_2',
        ];

        //发送红包
        $result = $redpack->sendNormal($redpackData);

        //添加流水记录
        $redpackBillData = [
            'coupon_batch_id' => $coupon->coupon_batch_id,
            'coupon_code' => $coupon->code,
        ];

        $redpackBillData = array_merge($redpackData, $redpackBillData, $result);

        RedPackBill::query()->create($redpackBillData);

        //标记优惠券为已使用-不再发放现金红包
        $coupon->update(['status' => 1]);

        //如果错误 封装成 500, 方便前端处理
        if ($result['result_code'] == 'FAIL') {
//            ding()->with()->text();
            abort(500, $result['return_msg']);
        }

        return $result;
    }

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