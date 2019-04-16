<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\MallCoo\V1\Request\VerificationCodeRequest;
use App\Http\Controllers\Controller;
use Log;

class VerificationCodesController extends Controller
{

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

    /**
     * curl get 请求
     * @param string $url
     * @param array $data
     * @param int $timeout
     * @return mixed
     */
    private function curl_get($url, $data = [], $timeout = 5)
    {
        abort_if($url == "" || $timeout <= 0, 500, 'invalid params!');
        $url = $url . '?' . http_build_query($data);

        $curl = curl_init((string)$url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, (int)$timeout);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * 一信通短信接口
     * @param string $phone 手机号
     * @param string $content 短信内容
     * @return string
     */
    private function send($phone, $content)
    {
        $data = [
            "SpCode" => "229266",
            "LoginName" => 'wj_wygc',
            "Password" => "d046a33bab974703",
            "MessageContent" => $content,
            "UserNumber" => $phone,
            "SerialNumber" => "",
            "ScheduleTime" => "",
            "f" => "1",
        ];

        foreach ($data as $key => $item) {
            $params[$key] = mb_convert_encoding($item, "GB2312", "UTF-8");
        }

        $url = "https://api.ums86.com:9600/sms/Api/Send.do";
        $result = $this->curl_get($url, $params);

        return mb_convert_encoding($result, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
    }

}
