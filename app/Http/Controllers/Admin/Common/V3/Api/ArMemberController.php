<?php

namespace App\Http\Controllers\Admin\Common\V3\Api;

use App\Http\Controllers\Admin\User\V1\Transformer\ArMemberTransformer;
use App\Http\Controllers\Admin\Common\V3\Request\ArMemberRequest;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberSession;
use App\Http\Controllers\Controller;
use Overtrue\EasySms\EasySms;
use Cache;
use Log;

class ArMemberController extends Controller
{

    /**
     * 生成验证码
     * @param ArMemberRequest $request
     * @param EasySms $easySms
     * @return \Dingo\Api\Http\Response
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @throws \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
     * @throws \Exception
     */
    public function sendVerificationCodes(ArMemberRequest $request, EasySms $easySms)
    {
        $phone = $request->get('phone');

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $easySms->send($phone, [
                    'content' => "【星视度】您正在使用星视度广告系统，短信验证码：$code ， 有效期为600秒，感谢您的使用！",
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $exception) {
                $response = $exception->getResponse();
                $result = json_decode($response->getBody()->getContents(), true);
                return $this->response->errorInternal($result['msg'] ?? '短信发送异常');
            }
        }

        $key = 'verificationCode_' . str_random(15);
        $expiredAt = now()->addMinutes(10);
        // 缓存短信验证码 10分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }

    /**
     * 绑定手机
     * @param ArMemberRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(ArMemberRequest $request)
    {
        $verifyData = Cache::get($request->verification_key);
        abort_if(!$verifyData, 422,'验证码已失效');
        abort_if(!hash_equals($verifyData['code'], $request->get('verification_code')), 401, '验证码错误');

        /** @var ArMemberSession $session */
        $session = ArMemberSession::query()->where('z', $request->get('z'))->firstOrFail();
        $session->arMember->update(['mobile' => $verifyData['phone']]);

        return $this->response->item($session->arMember, new ArMemberTransformer());
    }

}
