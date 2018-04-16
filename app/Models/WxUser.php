<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent as Model;

class WxUser extends  Model
{
    use SoftDeletes, Authenticatable;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $primaryKey='openid';

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
        'authorizer_appid',
    ];
}
