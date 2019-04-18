<?php

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch
 *
 * @property int $id
 * @property int $coupon_batch_id
 * @property int $marketid 商场ID
 * @property int|null $oid 门店ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market $market
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point|null $point
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch whereCouponBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Coupon\V1\Models\MarketPointCouponBatch whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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