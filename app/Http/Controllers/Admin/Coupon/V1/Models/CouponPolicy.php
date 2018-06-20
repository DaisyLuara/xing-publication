<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:27
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;


use App\Models\Model;

class CouponPolicy extends Model
{
    protected $fillable=[
        'age',
        'sex',
        'chance',
    ];
    public function couponBatch(){
        return $this->hasMany(CouponBatch::class,'id','coupon_batch_id');
    }
}