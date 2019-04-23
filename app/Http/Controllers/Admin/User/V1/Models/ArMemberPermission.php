<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission
 *
 * @property int $uid
 * @property string $z 密钥
 * @property string|null $mobile 手机号
 * @property string|null $username 昵称
 * @property string|null $face 头像
 * @property int $dev 开发
 * @property int $des 设计
 * @property int $bd 市场
 * @property int $opera 运营
 * @property int $pro 产品
 * @property int $analysis 数据分析
 * @property int $inside 内部
 * @property string|null $regdate 注册日期
 * @property string $date 时间
 * @property int $clientdate 时间轴
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereAnalysis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereBd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereDes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereDev($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereInside($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereOpera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission wherePro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereRegdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission whereZ($value)
 * @mixin \Eloquent
 */
class ArMemberPermission extends Model
{
    protected $connection = 'ar';
    public $table = 'news_user_permission';
    protected $primaryKey = 'uid';
}
