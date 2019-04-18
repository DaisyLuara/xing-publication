<?php

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Models\Model;

/**
 * Class ArWxUser
 *
 * @package App\Http\Controllers\Admin\Activity\V1\Models
 * @property int openid
 * @property int $id
 * @property int $uid 用户UID
 * @property int $fpid 人脸ID
 * @property string $openid 第三方ID
 * @property string|null $unionid 平台ID
 * @property int $wiid 平台ID
 * @property int $wx_ptid 通行证种类ID
 * @property string $date 时间
 * @property int $clientdate 时间轴
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser whereFpid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser whereUnionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser whereWiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\ArWxUser whereWxPtid($value)
 * @mixin \Eloquent
 */
class ArWxUser extends Model
{
    protected $connection = 'ar';
    protected $table = 'news_user_open';
    public $timestamps = false;

}
