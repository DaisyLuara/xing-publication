<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WxThird extends Model
{
    protected $connection='ar';
    public $table='wx_third_info';

    public $fillable=[
        'appid',
        'expires_in',
        'access_token',
        'refresh_token',
        'nick_name',
        'user_name',
        'head_img',
        'qrcode_url',
        'url',
        'service_type',
        'verify_type',
        'date',
        'clientdate',
    ];
    public function projectAdLaunch()
    {
        return $this->hasOne(ProjectAdLaunch::class,'wiid','id');
    }
}
