<?php

namespace App\Gateways;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Exceptions\GatewayErrorException;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;
use Overtrue\EasySms\Traits\HasHttpRequest;

/**
 * Class UmsGateway.
 *
 * @see https://www.ums86.com/
 */
class UmsGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'https://api.ums86.com:9600/sms/Api/Send.do';

    const ENDPOINT_SP_CODE = '229266';

    const ENDPOINT_F = 1;

    /**
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface $to
     * @param \Overtrue\EasySms\Contracts\MessageInterface $message
     * @param \Overtrue\EasySms\Support\Config $config
     *
     * @return array
     *
     * @throws \Overtrue\EasySms\Exceptions\GatewayErrorException ;
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $data = [
            'SpCode' => self::ENDPOINT_SP_CODE,
            'LoginName' => $config->get('account'),
            'Password' => $config->get('password'),
            'MessageContent' => $message->getContent($this),
            'UserNumber' => $to->getNumber(),
            'SerialNumber' => '',
            'ScheduleTime' => '',
            'f' => self::ENDPOINT_F,
        ];

        $params = [];
        foreach ($data as $key => $item) {
            $params[$key] = mb_convert_encoding($item, 'GB2312', 'UTF-8');
        }

        /** @var String $responseStr */
        $responseStr = $this->get(self::ENDPOINT_URL, $params);
        $responseStr = mb_convert_encoding($responseStr, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
        parse_str($responseStr, $responseArr);

        if ($responseArr['result'] != 0) {
            throw new GatewayErrorException($responseArr['description'], $responseArr['result'], $responseArr);
        }

        return $responseArr;
    }

}
