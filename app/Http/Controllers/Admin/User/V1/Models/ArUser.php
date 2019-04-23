<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\User\V1\Models\ArUser
 *
 * @property int $uid
 * @property string $user
 * @property string $pwd
 * @property string|null $realname 真是姓名
 * @property string|null $mobile 手机号
 * @property int|null $role_id
 * @property int $push 推送接收
 * @property int $hibi 嗨币
 * @property int $hidou 嗨豆
 * @property int $recharge 押金
 * @property int $lv 等级
 * @property int $vip VIP 0:否 1:是
 * @property int $agent 代理 0：否 1：是
 * @property int|null $company_id
 * @property int|null $production_id
 * @property string $z 用户标识
 * @property string|null $openid 微信openid
 * @property int|null $add_ts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Point\V1\Models\Point[] $points
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereAddTs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereHibi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereHidou($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereLv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereProductionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser wherePush($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser wherePwd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereRealname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereRecharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereVip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArUser whereZ($value)
 * @mixin \Eloquent
 */
class ArUser extends Model
{
    protected $connection = 'ar';
    public $table = 'admin_staff';
    protected $primaryKey = 'uid';

    public function points()
    {
        return $this->belongsToMany(Point::class, 'admin_per_oid', 'uid', 'oid');
    }
}
