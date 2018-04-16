<?php
/**
 * Created by PhpStorm.
 * User: jhk
 * Date: 2018/3/22
 * Time: 上午11:01
 */

namespace App\Libraries\GizWits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Log;

class GizWitsClient
{

    private $app_id = '';
    private $username = '';
    private $password = '';
    private $user_token = '';
    private $http_client = null;
    private $base_uri = 'https://api.gizwits.com/';
    private $options = null;
    private $device_attr = '';
    private $open_ids = [] ;
    private $template_id;

    public function __construct($app_id, $username, $password, $attr, $open_ids, $template_id)
    {
        $this->app_id = $app_id;
        $this->username = $username;
        $this->password = $password;
        $this->device_attr = $attr;
        $this->open_ids = $open_ids;
        $this->template_id = $template_id;

        $this->http_client = new Client();
        $this->options = [
            'headers' => [
                'X-Gizwits-Application-Id' => $this->app_id,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ];

    }

    public function setUserToken()
    {
        $user_token = Cache::get($this->username);
        if ($user_token) {
            $this->user_token = $user_token;
        } else {
            $this->user_token = $this->getUserToken();
        }

        $this->options['headers']['X-Gizwits-User-token'] = $this->user_token;
    }

    public function getUserToken()
    {
        $url = $this->base_uri . 'app/login';
        $this->options['json'] = [
            'username' => $this->username,
            'password' => $this->password,
        ];

        $res = $this->http_client->request('POST', $url, $this->options)->getBody();
        $contents = json_decode($res->getContents());
        $user_token = $contents->token;
        $expire_at = $contents->expire_at;
        //添加缓存必须设置时间
        Cache::put($this->username, $user_token, ($expire_at - time()) / 60);
        return $user_token;
    }

    public function start($device_id)
    {
        $this->options['json'] = ['attrs' => [$this->device_attr => 1]];
        $url = $this->base_uri . 'app/control/' . $device_id;
        $res = $this->http_client->request('POST', $url, $this->options);
        return $res->getBody()->getContents();
    }

    public function stop($device_id)
    {
        $this->options['json'] = ['attrs' => [$this->device_attr => 0]];
        $url = $this->base_uri . 'app/control/' . $device_id;
        $res = $this->http_client->request('POST', $url, $this->options);
        return $res->getBody()->getContents();
    }


    public function deviceDetail($device_id)
    {
        $url = $this->base_uri . 'app/devices/' . $device_id;
        $res = $this->http_client->request('get', $url, $this->options);
        return $res->getBody()->getContents();
    }

    public function restart($device_id)
    {
        $this->stop($device_id);
        sleep(2);
        $result = $this->latest($device_id);
        $result = json_decode($result, true);
        if ($result['attr'][$this->device_attr] == 0) {
            return $this->start($device_id);
        }
    }

    public function latest($device_id)
    {
        $url = $this->base_uri . 'app/devdata/' . $device_id . '/latest';
        $res = $this->http_client->request('get', $url, $this->options);
        return $res->getBody()->getContents();
    }

    public function users()
    {
        $url = $this->base_uri . 'app/users';
        $res = $this->http_client->request('GET', $url, $this->options);
        return $res->getBody()->getContents();
    }

    public function bindings()
    {
        $url = $this->base_uri . 'app/bindings';
        $res = $this->http_client->request('GET', $url, $this->options);
        return $res->getBody()->getContents();
    }

    public function getOpenIds(){
        return $this->open_ids;
    }

    public function getTemplateId(){
        return $this->template_id;
    }
}