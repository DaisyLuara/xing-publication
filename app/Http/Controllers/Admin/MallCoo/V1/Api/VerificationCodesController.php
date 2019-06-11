<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Gateways\UmsGateway;
use App\Http\Controllers\Admin\MallCoo\V1\Request\VerificationCodeRequest;
use App\Http\Controllers\Controller;
use App\Gateways\YouxuntongGateway;
use Overtrue\EasySms\EasySms;
use Log;

class VerificationCodesController extends Controller
{

    /**
     * UMS(吾悦)
     * @param VerificationCodeRequest $request
     * @throws \Exception
     */
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->get('phone');

        // 注册
        $easySms->extend('ums', function () {
            return new UmsGateway(config('easysms.gateways.ums'));
        });

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            $content = '尊敬的用户，您的验证码是' . $code . '，30分钟内有效。为保障账户安全，请勿泄露。';

            try {
                $easySms->send($phone, [
                    'content' => $content,
                ]);

            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('ums')->getMessage();
                return $this->response->errorInternal($message ?: '短信发送异常');
            }

        }

        $key = 'verificationCode_' . str_random(15);
        $expiredAt = now()->addMinutes(30);
        // 缓存短信验证码 30分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }

    /**
     * 优讯通(荟聚)
     * @param VerificationCodeRequest $request
     * @param EasySms $easySms
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @throws \Exception
     */
    public function sendVerificationCodes(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->get('phone');

        // 注册
        $easySms->extend('youxuntong', function () {
            return new YouxuntongGateway(config('easysms.gateways.youxuntong'));
        });

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            $content = '尊敬的用户，您的验证码是' . $code . '，30分钟内有效。为保障账户安全，请勿泄露。';

            try {
                $easySms->send($phone, [
                    'content' => $content,
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('youxuntong')->getMessage();
                return $this->response->errorInternal($message ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_' . str_random(15);
        $expiredAt = now()->addMinutes(30);
        // 缓存短信验证码 30分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }


}
