<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Models;

use App\Models\ArModel;

/**
 * App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer
 *
 * @property int $id
 * @property string $appid 公众号appid
 * @property int $expires_in 失效时间
 * @property string $access_token 令牌
 * @property string $refresh_token 刷新令牌
 * @property string $nick_name 名称
 * @property string $user_name 原始ID
 * @property string|null $head_img 图标
 * @property string $qrcode_url 二维码
 * @property string|null $url 实际二维码地址
 * @property int $service_type 0小程序，1订阅号，2服务号,100手机号,101普通,102本地，200天猫
 * @property int $verify_type 授权方认证 -1：未认证 0：已认证 2：补充认证
 * @property int $state 状态
 * @property string|null $per 允许权限
 * @property int $creatdate 创建时间
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereAppid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereCreatdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereHeadImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereNickName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer wherePer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereQrcodeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WeChatAuthorizer whereVerifyType($value)
 * @mixin \Eloquent
 */
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
