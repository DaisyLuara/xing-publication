<?php
/**
 * Created by PhpStorm.
 * User: jhk
 * Date: 2018/3/17
 * Time: 下午3:33
 */

namespace App\Support;

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
    public function __construct($sMallid, $sAppID, $sPublicKey, $sPrivateKey){
        $this->m_Mallid = $sMallid;
        $this->m_AppID = $sAppID;
        $this->m_PublicKey = $sPublicKey;
        $this->m_PrivateKey = $sPrivateKey;
    }

    /**
     * mallcoo post 请求
     *
     * @param string $sUrl 请求url
     * @param array $aPostData 请求参数
     * @return array
     */
    public function send($sUrl, $aPostData = []){
        $sPostData = json_encode($aPostData);
        $nTimeStamp = date('YmdHis',time());
        $sS = "{publicKey:".$this->m_PublicKey.",timeStamp:".$nTimeStamp.",data:".$sPostData.",privateKey:".$this->m_PrivateKey."}";
        $sSign = strtoupper(substr(md5($sS), 8, 16));
        $aHeader = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($sPostData),
            'AppID: '.$this->m_AppID,
            'TimeStamp: '.$nTimeStamp,
            'PublicKey: '.$this->m_PublicKey,
            'Sign: '.$sSign,
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
    private function curl_post($url, $aHeader, $sParams, $cookie='') {
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
        if ($result === false){
            // log curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
}