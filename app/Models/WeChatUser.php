<?php

namespace App\Models;

use Eloquent as Model;

/**
 * App\Models\WeChatUser
 *
 * @property int $subscribe
 * @property string $nickname
 * @property int $sex 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
 * @property string $language
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $headimgurl
 * @property int $subscribe_time
 * @property string $unionid 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段
 * @property string $remark
 * @property int $groupid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $openid
 * @property string|null $xingstation_wx_open_id
 * @property string|null $authorizer_appid
 * @property string|null $component_appid
 * @property mixed|null $piwik_visitor_id
 * @property string|null $mobile
 * @property string|null $mallcoo_open_user_id 猫酷 open id
 * @property string|null $face_id
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereAuthorizerAppid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereComponentAppid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereFaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereGroupid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereHeadimgurl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereMallcooOpenUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser wherePiwikVisitorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereSubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereSubscribeTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereUnionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeChatUser whereXingstationWxOpenId($value)
 * @mixin \Eloquent
 */
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