<?php

namespace App\Gateways;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Exceptions\GatewayErrorException;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;
use Overtrue\EasySms\Traits\HasHttpRequest;

/**
 * Class YouxuntongGateway.
 *
 * @see http://www.jxapi.com/common/api/0/vercodesms
 */
class YouxuntongGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'http://new.yxuntong.com/emmpdata/sms/Submit';

    const ENDPOINT_VERSION = '2.0';

    const ENDPOINT_FORMAT = 'json';

    const ENDPOINT_SID = '';

    const SUCCESS_CODE = '0';

    const ENDPOINT_SIGN = '【无锡荟聚中心】';

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
        $urlParams = [
            'v' => self::ENDPOINT_VERSION,
            'type' => self::ENDPOINT_FORMAT,
            'sid' => self::ENDPOINT_SID,
        ];

        $params = [
            'account' => $config->get('account'),
            'password' => md5($config->get('password')),
            'phones' => $to->getUniversalNumber(),
            'content' => $message->getContent($this),
            'sign' => self::ENDPOINT_SIGN,
            'subcode' => '',
            'sendtime' => '',
        ];

        $result = $this->postJson($this->getEndpointUrl($urlParams), $params);
        /** @var string $result */
        $responseArr = json_decode($result, true);

        if ($responseArr["result"] !== self::SUCCESS_CODE) {
            throw new GatewayErrorException($responseArr['desc'], $responseArr['result'], $responseArr);
        }

        return $responseArr;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function getEndpointUrl($params)
    {
        return self::ENDPOINT_URL . '?' . http_build_query($params);
    }

}
