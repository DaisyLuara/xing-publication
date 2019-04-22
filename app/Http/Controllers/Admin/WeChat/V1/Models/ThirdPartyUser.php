<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Models;

use App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser
 *
 * @property int $id
 * @property bool $subscribe
 * @property string|null $nickname
 * @property string|null $username 用户姓名
 * @property string|null $avatar
 * @property int|null $age
 * @property int $gender 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
 * @property string|null $openid
 * @property string|null $mallcoo_wx_open_id 猫酷 WxOpenId
 * @property string|null $mobile
 * @property string|null $mallcoo_open_user_id 猫酷 OpenUserId
 * @property string|null $birthday
 * @property string|null $mall_card_apply_time 猫酷 会员开卡时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooScoreHistory[] $mallcoo_score_histories
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereMallCardApplyTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereMallcooOpenUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereMallcooWxOpenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereSubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser whereUsername($value)
 * @mixin \Eloquent
 */
class ThirdPartyUser extends Model
{

    public $table = 'third_party_users';
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
        'marketid',
        'wx_user_id',
        'z',
        'mallcoo_open_user_id',
        'birthday',
        'mallcoo_wx_open_id',
        'mall_card_apply_time',
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

    public function mallcoo_score_histories()
    {
        return $this->hasMany(MallcooScoreHistory::class, 'mallcoo_open_user_id', 'mallcoo_open_user_id');
    }


}