<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Models;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;

class Coupon extends Model
{
    protected $fillable = [
        'mobile',
        'coupon_batch_id',
        'code',
        'picm_id',
        'trace_id',
        'status',
        'wx_user_id',
        'taobao_user_id',
    ];

    public function couponBatch()
    {
        return $this->belongsTo(CouponBatch::class, 'coupon_batch_id', 'id');
    }

    public function media()
    {
        return $this->hasOne(Media::class);
    }
}
