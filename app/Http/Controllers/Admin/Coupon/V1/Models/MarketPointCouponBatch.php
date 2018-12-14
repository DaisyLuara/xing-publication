<?php

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

class MarketPointCouponBatch extends Model
{
    protected $fillable = [
        'marketid',
        'oid',
    ];

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

}