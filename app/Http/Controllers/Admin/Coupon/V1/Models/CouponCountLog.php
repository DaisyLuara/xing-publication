<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Models\Model;

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
