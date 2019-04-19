<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Models;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\WeChat\V1\Models\WxThird
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
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\ProjectAdLaunch $projectAdLaunch
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereAppid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereCreatdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereHeadImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereNickName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird wherePer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereQrcodeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\WxThird whereVerifyType($value)
 * @mixin \Eloquent
 */
class WxThird extends Model
{
    protected $connection = 'ar';
    public $table = 'wx_third_info';

    public function projectAdLaunch()
    {
        return $this->hasOne(ProjectAdLaunch::class, 'wiid', 'id');
    }
}
