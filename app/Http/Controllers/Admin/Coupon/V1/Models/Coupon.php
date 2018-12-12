<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\User\V1\Models\ArMember;
use App\Models\Model;
use App\Models\Customer;

class Coupon extends Model
{

    protected $dates = ['start_date', 'end_date', 'use_date'];

    protected $fillable = [
        'mobile',
        'coupon_batch_id',
        'code',
        'picm_id',
        'trace_id',
        'status',
        'wx_user_id',
        'taobao_user_id',
        'member_uid',
        'qiniu_id',
        'oid',
        'belong',
        'start_date',
        'end_date',
    ];

    public function couponBatch()
    {
        return $this->belongsTo(CouponBatch::class, 'coupon_batch_id', 'id');
    }

    public function media()
    {
        return $this->hasOne(Media::class);
    }

    public function member()
    {
        return $this->belongsTo(ArMember::class, 'member_uid', 'uid');
    }

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id', 'id');
    }
}
