<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\User\V1\Models\ArMember
 *
 * @property int $uid
 * @property int $qiniu_id 七牛ID
 * @property string|null $imei 唯一标示
 * @property int $cid 公司ID
 * @property string|null $username 用户昵称
 * @property string|null $password 密码
 * @property string|null $mobile 手机号
 * @property int $checkmobile 手机验证
 * @property int $age 年龄
 * @property int $byear 年
 * @property int $bmon 月
 * @property int $bday 日
 * @property int $gender 性别
 * @property int $xid 荣誉ID
 * @property string|null $ua 手机型号
 * @property string|null $email 邮箱
 * @property string|null $regip 注册IP
 * @property string|null $location 地址
 * @property int|null $regdate 注册日期
 * @property string $appkey 产品KEY
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereAppkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereBday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereBmon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereByear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereCheckmobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereImei($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereQiniuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereRegdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereRegip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMember whereXid($value)
 * @mixin \Eloquent
 */
class ArMember extends Model
{
    protected $connection = 'ar';
    public $table = 'news_members';
    protected $primaryKey = 'uid';
    public $timestamps = false;

    protected $fillable = [
        'mobile',
    ];

    public function ar_user(){
        return $this->hasOne(ArUser::class,'mobile','mobile');
    }
}
