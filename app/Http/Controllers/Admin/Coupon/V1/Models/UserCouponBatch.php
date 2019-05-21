<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;


use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Models\Model;
use App\Models\User;

/**
 * App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch
 *
 * @property int $id
 * @property int $wx_user_id
 * @property int $taobao_user_id
 * @property int $member_uid
 * @property int $coupon_batch_id
 * @property string|null $belong 节目版本名称
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch whereCouponBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch whereMemberUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch whereTaobaoUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch whereWxUserId($value)
 * @mixin \Eloquent
 */
class UserCouponBatch extends Model
{
    protected $fillable = [
        'wx_user_id',
        'tao_user_id',
        'member_uid',
        'coupon_batch_id',
        'belong',
    ];

    public function couponBatch()
    {
        return $this->belongsTo(CouponBatch::class, 'coupon_batch_id', 'id');
    }

}