<?php

namespace App\Traits;

use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use Log;

trait UmsSms
{
    /**
     * curl get 请求
     * @param string $url
     * @param array $data
     * @param int $timeout
     * @return mixed
     */
    private function curl_get($url, $data = [], $timeout = 5)
    {
        abort_if($url === '' || $timeout <= 0, 500, 'invalid params!');
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
            'SpCode' => '229266',
            'LoginName' => 'wj_wygc',
            'Password' => 'd046a33bab974703',
            'MessageContent' => $content,
            'UserNumber' => $phone,
            'SerialNumber' => '',
            'ScheduleTime' => '',
            'f' => '1',
        ];

        foreach ($data as $key => $item) {
            $params[$key] = mb_convert_encoding($item, 'GB2312', 'UTF-8');
        }

        $url = 'https://api.ums86.com:9600/sms/Api/Send.do';
        $result = $this->curl_get($url, $params);

        return mb_convert_encoding($result, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
    }

    /**
     * 发送券码短信
     * @param Coupon $coupon
     * @param int $wxUserId
     * @param int $marketid
     * @return mixed
     */
    public function sendCouponMsg($coupon, $wxUserId, $marketid)
    {
        $code = $coupon->code;
        $content = '尊敬的用户，您的验证码是' . $code . '，30分钟内有效。为保障账户安全，请勿泄露。';

        /** @var ThirdPartyUser $user */
        $user = ThirdPartyUser::query()->where('wx_user_id', $wxUserId)
            ->where('marketid', $marketid)->firstOrFail();

        try {
            $strData = $this->send($user->mobile, $content);
            Log::info('couponMsg.' . $user->mobile, ['strData' => $strData]);

            parse_str($strData, $resData);
            abort_if($resData['result'] != 0, 500, $resData['description']);

        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
            $result = json_decode($response->getBody()->getContents(), true);
            return $this->response->errorInternal($result['msg'] ?? '短信发送异常');
        }

    }

}
