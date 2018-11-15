<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Models;

use App\Models\ArModel;

class WeChatAuthorizer extends ArModel
{
    public $table = 'wx_third_info';

    protected $fillable = [
        'appid',
        'expires_in',
        'access_token',
        'refresh_token',
    ];
}
