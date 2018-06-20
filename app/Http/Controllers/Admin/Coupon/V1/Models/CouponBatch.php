<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;


use App\Models\Model;

class CouponBatch extends Model
{
    public function coupon(){
        return $this->hasMany(Coupon::class,'id','coupon_batch_id');
    }

    public function couponPolicy(){
        return $this->belongsTo(CouponPolicy::class,'id','coupon_policy_id');
    }
}