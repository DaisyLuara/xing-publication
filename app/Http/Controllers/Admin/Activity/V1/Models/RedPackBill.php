<?php
/**
 * Created by IntelliJ IDEA.
 * User: chenzhong
 * Date: 2019/1/22
 * Time: 下午5:39
 */

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Models\Model;

class RedPackBill extends Model
{
    protected $table = 'redpack_bill';

    protected $fillable = [
        'coupon_batch_id',
        'coupon_code',
        'mch_billno',
        'mch_id',
        'wxappid',
        'send_name',
        're_openid',
        'total_amount',
        'total_num',
        'scene_id',
        'return_code',
        'return_msg',
        'result_code',
        'err_code',
        'err_code_des',
        'send_listid',
        'remark',
    ];

    public function couponBatch()
    {
        return $this->belongsTo(CouponBatch::class, 'coupon_batch_id', 'id');
    }

}