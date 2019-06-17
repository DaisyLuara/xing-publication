<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\MallCoo\V1\Request\MallCooRequest;
use App\Http\Controllers\Admin\MallCoo\V1\Request\UserRequest;
use App\Http\Controllers\Admin\MallCoo\V1\Transformer\ThirdPartyUserTransformer;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Jobs\FaceBindingJob;
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

        $userID = decrypt($request->get('sign'));
        $redirect_url = urldecode($request->get('redirect_url'));
        $redirect_url = add_query_string($redirect_url, 'user_id', $userID);

        $callback_url = 'http://' . $request->getHost() . '/api/mallcoo/user/callback?oid=' . $request->get('oid') . '&redirect_url=' . urlencode($redirect_url);

        return $this->mall_coo->oauth($callback_url);
    }

    /**
     * 授权页面回调
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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

            $thirdPartyUser = ThirdPartyUser::query()->updateOrCreate(
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
     * 手机号开会员卡
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $wxUserId = decrypt($request->get('sign'));
        $verifyData = Cache::get($request->verification_key);
        abort_if(!$verifyData, 422,'验证码已失效');
        abort_if(!hash_equals($verifyData['code'], $request->get('verification_code')), 401, '验证码错误');

        //开卡接口
        $sUrl = 'https://openapi10.mallcoo.cn/User/MallCard/v1/Open/ByMobile/';
        $cardResult = $this->mall_coo->send($sUrl, ['Mobile' => $verifyData['phone']]);
        dd($cardResult);
//        abort_if(($cardResult['Code'] !== 1) && ($cardResult['Code'] !== 307), 500, $cardResult['Message']);

        //获取会员信息
        $userResult = $this->mall_coo->getUserInfoByOpenUserID($cardResult['Data']['OpenUserID']);
//        dd($userResult);
        abort_if(($userResult['Code'] !== 1) && ($userResult['Code'] !== 30018), 500, $userResult['Message']);


        $userInfo = $userResult['Data'];
        $user = ThirdPartyUser::query()->updateOrCreate(
            ['mallcoo_open_user_id' => $userInfo['OpenUserID']],
            [
                'mobile' => $userInfo['Mobile'],
                'username' => $userInfo['UserName'],
                'mallcoo_wx_open_id' => $userInfo['WXOpenID'],
                'gender' => $userInfo['Gender'],
                'birthday' => $userInfo['Birthday'] ?: null,
                'marketid' => $this->mall_coo->marketid,
                'wx_user_id' => $wxUserId,
                'belong' => $request->get('belong') ?? '',
                'mall_card_apply_time' => $userInfo['MallCardApplyTime'],
            ]
        );

        WeChatUser::query()->where('id', $wxUserId)->update(['mobile' => $user->mobile]);

        if ($request->has('qiniu_id')) {
//            dispatch(new FaceBindingJob($request->get('qiniu_id'), $user->mobile));
            FaceBindingJob::dispatch($request->get('qiniu_id'), $user->mobile)->onQueue('face-bind');
            //绑定人脸
            /** @var TodayFileUpload $fileUpload */
            $fileUpload = TodayFileUpload::query()->findOrFail($request->get('qiniu_id'));
            /** @var TodayFaceCollect $faceCollect */
            $faceCollect = TodayFaceCollect::query()->where('fpid', $fileUpload->fpid)->orderByDesc('clientdate')->first();

            if ($faceCollect) {
                $this->mall_coo->bindUserFace($faceCollect, $user->mobile);
            }
        }

        return $this->response->item($user, new ThirdPartyUserTransformer());
    }

    /**
     * 商场会员信息
     * @param UserRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function show(UserRequest $request)
    {
        $wxUserId = decrypt($request->get('sign'));

        /** @var ThirdPartyUser $user */
        $user = ThirdPartyUser::query()->where('wx_user_id', $wxUserId)
            ->where('marketid', $this->mall_coo->marketid)->first();

        abort_if(!$user, 204);

        return $this->response->item($user, new ThirdPartyUserTransformer());
    }

    public function test()
    {
        $sUrl = 'https://openapi10.mallcoo.cn/Facial/v2/Bind/UserFace/';

        $data = [
                    "Type" => 1,
                    "VendorID" => 5,
                    "FaceID" => "gDbgCXegKYCD6hMv",
                    "Mobile" => "18635467384",
                    "FaceImg" => "http://psi3spdxk.bkt.clouddn.com/1007/face/kiki_972_9331492923315123_111.jpg"
                ];

        $cardResult = $this->mall_coo->send($sUrl, $data);

        abort_if(($cardResult['Code'] !== 1) && ($cardResult['Code'] !== 307), 500, $cardResult['Message']);
    }

    public function getFace()
    {
        $sUrl = 'https://openapi10.mallcoo.cn/Facial/v2/Get/User/ByFace/';

        $data = [
            "VendorID" => 5,
            "FaceID" => "4a8p8698tleV341b",
//            "FaceImg" => "http://psi3spdxk.bkt.clouddn.com/1007/face/kiki_972_9331492923315123_111.jpg"
        ];

        $result = $this->mall_coo->send($sUrl, $data);
        dd($this->mall_coo->getUserInfoByOpenUserID($result['Data']['OpenUserID']));
    }

}
