<?php

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Models\Model;

class MarketPointCouponBatch extends Model
{
    protected $fillable = [
        'marketid',
        'oid',
    ];

}