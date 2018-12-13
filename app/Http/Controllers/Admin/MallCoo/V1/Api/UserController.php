<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\MallCoo\V1\Request\MallCooRequest;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Models\WeChatUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;


class UserController extends Controller
{
    public function oauth(MallCooRequest $request)
    {
        $userID = decrypt($request->sign);
        $redirect_url = $request->get('redirect_url');
        $redirect_url = add_query_string($redirect_url, 'user_id', $userID);

        $mall_coo = app('mall_coo');
        $callback_url = 'http://' . $request->getHost() . '/api/mallcoo/user/callback?redirect_url=' . urlencode(($redirect_url));

        return $mall_coo->oauth($callback_url);
    }

    public function callback(Request $request)
    {
        $ticket = $request->get('Ticket');

        //获取用户UserToken
        $mall_coo = app('mall_coo');
        $result = $mall_coo->getTokenByTicket($ticket);
        abort_if($result['Code'] !== 1, 500, $result['Message']);

        //获取会员信息
        $result = $mall_coo->getUserInfoByOpenUserID($result['Data']['OpenUserId']);
        abort_if($result['Code'] !== 1, 500, $result['Message']);

        $userInfo = $result['Data'];
        $openUserId = $userInfo['OpenUserID'];
        $mobile = $userInfo['Mobile'];
        $mallCooWxOpenId = $userInfo['WXOpenID'];
        $username = $userInfo['UserName'];
        $gender = $userInfo['Gender'];
        $birthday = $userInfo['Birthday'];
        $mallCardApplyTime = $userInfo['MallCardApplyTime'];

        $redirect_url = urldecode($request->get('redirect_url'));
        $redirect_url = add_query_string($redirect_url, 'open_user_id', $openUserId);

        DB::beginTransaction();
        try {

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

            WeChatUser::query()->updateOrCreate(['id' => $request->user_id], ['mallcoo_open_user_id' => $thirdPartyUser->mallcoo_open_user_id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();//事务回滚
            abort(500, $e->getMessage());
        }

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
        abort_if($result['Code'] != 1, 500, $result['Message']);

        return response()->json($result['Data']);
    }


}
