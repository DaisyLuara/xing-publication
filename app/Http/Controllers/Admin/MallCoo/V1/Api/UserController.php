<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\WeChat\V1\Models\WeChatUser;
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
        $userInfo = $mall_coo->getUserInfoByOpenUserID($result['Data']['OpenUserId']);

        $open_user_id = $userInfo['Data']['OpenUserId'];
        $mobile = $userInfo['Data']['Mobile'];
        $username = $userInfo['Data']['UserName'];
        $gender = $userInfo['Data']['Gender'];
        $birthday = $userInfo['Data']['Birthday'];

        $separator = strpos($redirect_url, '?') ? '&' : '?';
        $redirect_url = $redirect_url . $separator . 'open_user_id=' . $open_user_id;

        WeChatUser::updateOrCreate(
            ['mallcoo_open_user_id' => $open_user_id],
            [
                'mobile' => $mobile,
                'username' => $username,
                'gender' => $gender,
                'birthday' => $birthday
            ]
        );

        return redirect($redirect_url);
    }

    /**
     * 通过 Ticket 获取 Token
     *
     * @param string $sTicket Ticket
     * @return
     */
    public function GetTokenByTicket($sTicket)
    {
        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/User/OAuth/v1/GetToken/ByTicket/';
        return $mall_coo->send($sUrl, array('Ticket' => $sTicket));
    }

    /**
     *  根据UserToken获取用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserByToken(Request $request)
    {
        $this->validate($request, [
            'UserToken' => 'required'
        ]);

        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/User/BaseInfo/v1/Get/ByToken/';

        $data = [
            'UserToken' => $request->UserToken,
        ];

        $result = $mall_coo->send($sUrl, $data);
        abort_if($result['Code'] != 1, 500, $result['Message']);

        return response()->json($result['Data']);
    }


}
