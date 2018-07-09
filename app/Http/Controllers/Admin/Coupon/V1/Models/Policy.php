<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:27
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;


use App\Models\Model;

class Policy extends Model
{
    protected $fillable = [

    ];

    public function batches()
    {
        return $this->belongsToMany(CouponBatch::class)->withPivot(['rate', 'min_age', 'max_age', 'gender', 'type']);
    }
}