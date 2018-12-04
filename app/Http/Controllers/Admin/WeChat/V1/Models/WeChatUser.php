<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Models;

use App\Models\Model;

class WeChatUser extends Model
{

    public $table = 'wx_users';
    public $primaryKey = 'id';

    public $fillable = [
        'subscribe',
        'nickname',
        'gender',
        'avatar',
        'age',
        'username',
        'openid',
        'mobile',
        'mallcoo_open_user_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'subscribe' => 'boolean',
        'gendor' => 'boolean',
    ];


}