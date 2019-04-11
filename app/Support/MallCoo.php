<?php
/**
 * Created by PhpStorm.
 * User: jhk
 * Date: 2018/3/17
 * Time: 下午3:33
 */

namespace App\Support;

use App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooConfig;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use Cache;

class MallCoo
{
    protected $m_Mallid = '';
    protected $m_AppID = '';
    protected $m_PublicKey = '';
    protected $m_PrivateKey = '';

    /**
     * 初始化
     *
     * @param string $sMallid Mallid
     * @param string $sAppID AppID
     * @param string $sPublicKey 公钥
     * @param string $sPrivateKey 私钥
     */
    public function __construct($sMallid, $sAppID, $sPublicKey, $sPrivateKey)
    {
        $this->m_Mallid = $sMallid;
        $this->m_AppID = $sAppID;
        $this->m_PublicKey = $sPublicKey;
        $this->m_PrivateKey = $sPrivateKey;
    }

    /**
     * 授权链接
     * @param $sCallbackUrl
     * @return string
     */
    public function oauth($sCallbackUrl)
    {
        $sUrl = 'https://m.mallcoo.cn/a/open/User/V2/OAuth/CardInfo/';
        $sUrl .= '?AppID=' . $this->m_AppID . '&PublicKey=' . $this->m_PublicKey . '&CallbackUrl=' . urlencode($sCallbackUrl);
        return $sUrl;
    }

    /**
     * 通过 Ticket 获取 Token
     *
     * @param string $sTicket Ticket
     * @return
     */
    public function getTokenByTicket($sTicket)
    {
        $sUrl = 'https://openapi10.mallcoo.cn/User/OAuth/v1/GetToken/ByTicket/';
        return $this->send($sUrl, ['Ticket' => $sTicket]);
    }

    /**
     * 根据OpenUserID获取会员信息接口
     *
     * @param $openUserId
     * @return array
     */
    public function getUserInfoByOpenUserID($openUserId)
    {
        $sUrl = 'https://openapi10.mallcoo.cn/User/AdvancedInfo/v1/Get/ByOpenUserID/';
        return $this->send($sUrl, ['OpenUserId' => $openUserId]);
    }

    /**
     * 通过用户ID发券接口
     * @param string $open_user_id
     * @param string $picmID
     * @return array
     */
    public function sendCouponByOpenUserID($open_user_id, $picmID)
    {
        $sUrl = 'https://openapi10.mallcoo.cn/Coupon/v1/Send/ByOpenUserID/';

        $data = [
            'UserList' => [
                [
                    'BussinessID' => null,
                    'TraceID' => uniqid() . $this->m_AppID,
                    'PICMID' => $picmID,
                    'OpenUserID' => $open_user_id,
                ]
            ]
        ];

        return $this->send($sUrl, $data);
    }

    /**
     * mallcoo post 请求
     *
     * @param string $sUrl 请求url
     * @param array $aPostData 请求参数
     * @return array
     */
    public function send($sUrl, $aPostData = [])
    {
        $sPostData = json_encode($aPostData);
        $nTimeStamp = date('YmdHis', time());
        $sS = "{publicKey:" . $this->m_PublicKey . ",timeStamp:" . $nTimeStamp . ",data:" . $sPostData . ",privateKey:" . $this->m_PrivateKey . "}";
        $sSign = strtoupper(substr(md5($sS), 8, 16));
        $aHeader = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($sPostData),
            'AppID: ' . $this->m_AppID,
            'TimeStamp: ' . $nTimeStamp,
            'PublicKey: ' . $this->m_PublicKey,
            'Sign: ' . $sSign,
        );
        $sR = $this->curl_post($sUrl, $aHeader, $sPostData);
        return json_decode(html_entity_decode($sR), true);
    }

    /**
     * curl post 请求
     *
     * @param string $url
     * @param array $aHeader
     * @param string $sParams
     * @param string $cookie
     * @return string
     */
    private function curl_post($url, $aHeader, $sParams, $cookie = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sParams);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        if ($result === false) {
            // log curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    /**
     * 猫酷参数配置
     * @param integer $oid
     * @return $this
     */
    public function setMallCooConfig($oid, $prefix = 'mallcoo_config_')
    {
        $cacheIndex = $prefix . $oid;

        $mallCooConfig = Cache::rememberForever($cacheIndex, function () use ($oid) {
            $point = Point::query()->findOrFail($oid);
            $mallCooConfig = MallcooConfig::query()->where('marketid', $point->market->marketid)->firstOrFail();

            return $mallCooConfig;
        });

        $this->marketid = $mallCooConfig->marketid;
        $this->m_Mallid = $mallCooConfig->mallcoo_mall_id;
        $this->m_AppID = $mallCooConfig->mallcoo_appid;
        $this->m_PublicKey = $mallCooConfig->mallcoo_public_key;
        $this->m_PrivateKey = $mallCooConfig->mallcoo_private_key;

        return $this;
    }
}