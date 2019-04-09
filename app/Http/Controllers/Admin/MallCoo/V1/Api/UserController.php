<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\MallCoo\V1\Request\MallCooRequest;
use App\Http\Controllers\Admin\MallCoo\V1\Request\UserRequest;
use App\Http\Controllers\Admin\MallCoo\V1\Transformer\ThirdPartyUserTransformer;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Http\Request;
use App\Models\WeChatUser;
use Cache;
use DB;
use Log;


class UserController extends BaseController
{
    /**
     * 获取授权页面链接
     * @param MallCooRequest $request
     * @return mixed
     */
    public function oauth(MallCooRequest $request)
    {
        $request->validate([
            'redirect_url' => 'required|url',
            'sign' => 'required',
        ]);

        $userID = decrypt($request->sign);
        $redirect_url = urldecode($request->get('redirect_url'));
        $redirect_url = add_query_string($redirect_url, 'user_id', $userID);

        $callback_url = 'http://' . $request->getHost() . '/api/mallcoo/user/callback?oid=' . $request->oid . '&redirect_url=' . urlencode(($redirect_url));

        return $this->mall_coo->oauth($callback_url);
    }

    public function callback(Request $request)
    {
        $ticket = $request->get('Ticket');

        //获取用户UserToken
        $result = $this->mall_coo->getTokenByTicket($ticket);
        abort_if($result['Code'] !== 1, 500, $result['Message']);

        //获取会员信息
        $result = $this->mall_coo->getUserInfoByOpenUserID($result['Data']['OpenUserId']);
        abort_if($result['Code'] !== 1, 500, $result['Message']);

        $userInfo = $result['Data'];
        $openUserId = $userInfo['OpenUserID'];
        $mobile = $userInfo['Mobile'];
        $mallCooWxOpenId = $userInfo['WXOpenID'];
        $username = $userInfo['UserName'];
        $gender = $userInfo['Gender'];
        $birthday = $userInfo['Birthday'];
        $mallCardApplyTime = $userInfo['MallCardApplyTime'];

        $redirect_url = urldecode(urldecode($request->get('redirect_url')));
        $queries = parse_query(parse_url($redirect_url, PHP_URL_QUERY), false);
        Log::info('redirect_queries', $queries);
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
                    'marketid' => $this->mall_coo->marketid,
                    'mall_card_apply_time' => $mallCardApplyTime,
                ]
            );

            WeChatUser::query()->updateOrCreate(['id' => $queries['user_id']], ['mallcoo_open_user_id' => $thirdPartyUser->mallcoo_open_user_id]);
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

        $sUrl = 'https://openapi10.mallcoo.cn/Shop/V1/GetList/';

        $data = [
            "PageIndex" => 1,
            "PageSize" => null,
            "FloorID" => null,
            "CommercialTypeID" => null,
        ];

        $result = $this->mall_coo->send($sUrl, $data);
        abort_if($result['Code'] != 1, 500, $result['Message']);

        return response()->json($result['Data']);
    }

    /**
     * 手机号开会员卡
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $verifyData = Cache::get($request->verification_key);
        abort_if(!$verifyData, 422,'验证码已失效');
        abort_if(!hash_equals($verifyData['code'], $request->verification_code), 401, '验证码错误');

        //查询会员
        $user = ThirdPartyUser::query()->where('mobile', $verifyData['phone'])
            ->where('marketid', $this->mall_coo->marketid)->first();

        if ($user) {
            return $this->response->item($user, new ThirdPartyUserTransformer());
        }

        //开卡接口
        $sUrl = 'https://openapi10.mallcoo.cn/User/MallCard/v1/Open/ByMobile/';
        $cardResult = $this->mall_coo->send($sUrl, ['Mobile' => $verifyData['phone']]);
        abort_if(($cardResult['Code'] != 1) && ($cardResult['Code'] != 307), 500, $cardResult['Message']);

        //获取会员信息
        $userResult = $this->mall_coo->getUserInfoByOpenUserID($cardResult['Data']['OpenUserID']);
        abort_if($userResult['Code'] !== 1, 500, $userResult['Message']);

        $userInfo = $userResult['Data'];
        $user = ThirdPartyUser::updateOrCreate(
            ['mallcoo_open_user_id' => $userInfo['OpenUserID']],
            [
                'mobile' => $userInfo['Mobile'],
                'username' => $userInfo['UserName'],
                'mallcoo_wx_open_id' => $userInfo['WXOpenID'],
                'gender' => $userInfo['Gender'],
                'birthday' => $userInfo['Birthday'] ?: null,
                'marketid' => $this->mall_coo->marketid,
                'mall_card_apply_time' => $userInfo['MallCardApplyTime'],
            ]
        );

        return $this->response->item($user, new ThirdPartyUserTransformer());
    }

}
