<?php

namespace App\Models;

use Eloquent as Model;

class WeChatUser extends Model
{
    public $connection = 'jingsaas';
    public $table = 'wx_users';

    public $fillable = [
        'subscribe',
        'nickname',
        'sex',
        'language',
        'city',
        'province',
        'country',
        'headimgurl',
        'subscribe_time',
        'unionid',
        'remark',
        'groupid',
        'openid',
        'xingstation_wx_open_id',
        'authorizer_appid',
        'component_appid',
        'piwik_visitor_id',
        'mallcoo_open_user_id',
        'face_id',
        'mobile',
        'id'
    ];

}