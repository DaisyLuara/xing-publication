<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use function GuzzleHttp\Psr7\parse_query;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hashids\Hashids;


class UserController extends Controller
{
    public function oauth(Request $request)
    {
        $this->validate($request, [
            'redirect_url' => 'required|url'
        ]);

        $redirect_url = $request->get('redirect_url');

        $mall_coo = app('mall_coo');
        $callback_url = 'http://' . $request->getHost() . '/api/mallcoo/user/callback?redirect_url=' . urlencode(($redirect_url));

        return $mall_coo->oauth($callback_url);
    }

    public function callback(Request $request)
    {
        $redirect_url = urldecode($request->get('redirect_url'));

        $ticket = $request->get('Ticket');

        $queries = parse_query(parse_url($redirect_url, PHP_URL_QUERY), false);

        //获取用户UserToken
        $mall_coo = app('mall_coo');
        $result = $mall_coo->getTokenByTicket($ticket);

        if ($result['Code'] !== 1) {
            return $result['Message'];
        }

        //获取会员信息
        $response = $mall_coo->getUserInfoByOpenUserID($result['Data']['OpenUserId']);
        if ($response['Code'] !==1) {
            return $response['Message'];
        }

        $userInfo          = $response['Data'];
        $openUserId        = $userInfo['OpenUserID'];
        $mobile            = $userInfo['Mobile'];
        $mallCooWxOpenId   = $userInfo['WXOpenID'];
        $username          = $userInfo['UserName'];
        $gender            = $userInfo['Gender'];
        $birthday          = $userInfo['Birthday'];
        $mallCardApplyTime = $userInfo['MallCardApplyTime'];

        $separator = strpos($redirect_url, '?') ? '&' : '?';
        $redirect_url = $redirect_url . $separator . 'open_user_id=' . $openUserId;

        $thirdPartyUser = ThirdPartyUser::updateOrCreate(
            ['mallcoo_open_user_id' => $openUserId],
            [
                'mobile' => $mobile,
                'username' => $username,
                'mallcoo_wx_open_id' => $mallCooWxOpenId,
                'gender' => $gender,
                'birthday' => $birthday,
                'mall_card_apply_time' => $mallCardApplyTime,
            ]
        );

        return redirect($redirect_url);
    }

    /**
     *  根据UserToken获取用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserByToken(Request $request)
    {
        $this->validate($request, [
            'OpenUserId' => 'required'
        ]);

        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/Shop/V1/GetList/';

        $data = [
              "PageIndex" => 1,
              "PageSize" => null,
              "FloorID" => null,
              "CommercialTypeID" => null,
        ];

        $result = $mall_coo->send($sUrl, $data);
        dd($result);
        abort_if($result['Code'] != 1, 500, $result['Message']);

        return response()->json($result['Data']);
    }


}
