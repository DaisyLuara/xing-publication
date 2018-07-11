<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Models\Model;

class Coupon extends Model
{
    public function couponBatch(){
        return $this->belongsTo(CouponBatch::class,'id','coupon_batch_id');
    }
}
