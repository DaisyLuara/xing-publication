<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo
 *
 * @property int $uid
 * @property int $groupid 用户组
 * @property int $credits 积分
 * @property int $share 分享数量
 * @property int $money 金钱
 * @property int $rmb 金币
 * @property string|null $face 表情
 * @property string|null $idor 小偶头像
 * @property string|null $signed 签名
 * @property int $views 浏览量
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereGroupid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereIdor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereRmb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereShare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberInfo whereViews($value)
 * @mixin \Eloquent
 */
class ArMemberInfo extends Model
{
    protected $connection = 'ar';
    public $table = 'news_memberfields';
    protected $primaryKey = 'uid';
}
