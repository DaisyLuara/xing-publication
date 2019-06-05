<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\MallCoo\V1\Request\VerificationCodeRequest;
use App\Http\Controllers\Controller;
use App\Traits\UmsSms;
use Log;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    use UmsSms;

    /**
     * 生成验证码
     * @param VerificationCodeRequest $request
     * @throws \Exception
     */
    public function store(VerificationCodeRequest $request)
    {
        $phone = $request->phone;

        if (!app()->environment('production')) {
            $code = "1234";
        } else {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            $content = "尊敬的用户，您的验证码是" . $code . "，30分钟内有效。为保障账户安全，请勿泄露。";

            try {
                $strData = $this->send($phone, $content);
                Log::info('verificationCodes.' . $phone, ['strData' => $strData]);

                parse_str($strData, $resData);
                abort_if($resData['result'] != 0, 500, $resData['description']);

            } catch (\GuzzleHttp\Exception\ClientException $exception) {
                $response = $exception->getResponse();
                $result = json_decode($response->getBody()->getContents(), true);
                return $this->response->errorInternal($result['msg'] ?? '短信发送异常');
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

    public function sendVerificationCodes(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->get('phone');

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            $content = "【星视度】尊敬的用户您好，您的短信码：$code ， 有效期为10分钟，感谢您的参与！为保障您的账户安全，请勿泄露。";

            try {
                $easySms->send($phone, [
                    'content' => $content,
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


}
