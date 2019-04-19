<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Coupon\V1\Models\CouponCountLog
 *
 * @property-read \App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch $couponBatch
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\CouponCountLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\CouponCountLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\CouponCountLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @mixin \Eloquent
 */
class CouponCountLog extends Model
{
    protected $fillable = [
        'coupon_batch_id',
        'create_num',
        'receive_num',
        'unreceived_num',
        'date',
    ];

    public function couponBatch()
    {
        return $this->belongsTo(CouponBatch::class, 'coupon_batch_id', 'id');
    }
}
