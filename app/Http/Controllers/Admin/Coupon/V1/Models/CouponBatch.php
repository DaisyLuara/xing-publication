<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;


use App\Http\Controllers\Admin\Activity\V1\Models\Activity;
use App\Http\Controllers\Admin\Activity\V1\Models\ActivityCouponBatch;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Models\Model;
use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;

class CouponBatch extends Model
{
    use LogsActivity;

    protected $fillable = [
        'company_id',
        'create_user_id',
        'bd_user_id',
        'image_url',
        'bs_image_url',
        'amount',
        'count',
        'stock',
        'people_max_get',
        'pmg_status',
        'day_max_get',
        'dmg_status',
        'is_fixed_date',
        'delay_effective_day',
        'effective_day',
        'start_date',
        'end_date',
        'is_active',
        'name',
        'description',
        'third_code',
        'type',
        'redirect_url',
        'title',
        'campaign_id',
        'credit',
        'sort_order',
        'dynamic_stock_status',
        'write_off_status',
        'channel',
        'wechat_coupon_batch_id',
    ];

    public function coupon()
    {
        return $this->hasMany(Coupon::class, 'id', 'coupon_batch_id');
    }

    public function policy()
    {
        return $this->belongsToMany(Policy::class)->withPivot(['rate', 'min_age', 'max_age', 'gender', 'type']);
    }

    public function couponCountLog()
    {
        return $this->hasOne(CouponCountLog::class, 'id', 'coupon_batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'create_user_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function wechat()
    {
        return $this->hasOne(WechatCouponBatch::class, 'id', 'wechat_coupon_batch_id')->withDefault();
    }

    public function activityCouponBatches()
    {
        return $this->hasMany(ActivityCouponBatch::class, 'coupon_batch_id', 'id');
    }
}