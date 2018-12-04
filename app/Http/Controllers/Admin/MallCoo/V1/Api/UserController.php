<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;


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
        $short_url_obj = ShortUrl::query()->create(
            ['target_url' => $callback_url,
                'source' => 1,
                'url_type' => 0,
            ]
        );

        $callback_url = 'http://' . $request->getHost() . '/api/s/' . $short_url_obj->url;
        return $mall_coo->oauth($callback_url);
    }

    public function callback(Request $request)
    {
        $redirect_url = urldecode($request->get('redirect_url'));
        $ticket = $request->get('Ticket');

        $queries = parse_query(parse_url($redirect_url, PHP_URL_QUERY), false);

        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/User/OAuth/v1/GetToken/ByTicket/';
        $result = $mall_coo->send($sUrl, ['Ticket' => $ticket]);
        if ($result['Code'] !== 1) {
            return $result;
        }

        $open_user_id = $result['Data']['OpenUserId'];
        $mobile = $result['Data']['Mobile'];

        $separator = strpos($redirect_url, '?') ? '&' : '?';
        $redirect_url = $redirect_url . $separator . 'open_user_id=' . $open_user_id;

        WeChatUser::updateOrCreate(
            ['mallcoo_open_user_id' => $open_user_id],
            [
                'mobile' => $mobile,
                'face_id' => isset($queries['face_id']) ? $queries['face_id'] : '',
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

}
