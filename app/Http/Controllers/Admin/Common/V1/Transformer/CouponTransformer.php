<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Common\V1\Transformer;

use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;

class CouponTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['couponBatch'];

    public function transform(Coupon $coupon)
    {
        return [
            'code' => $coupon->code,
            'name' => $coupon->couponBatch->name,
            'status' => (int)$coupon->status,
            'qrcode_url' => $coupon->qrcode_url,
            'created_at' => $coupon->created_at->toDatetimeString(),
            'start_date' => $coupon->start_date ? $coupon->start_date : '',
            'end_date' => $coupon->end_date ? $coupon->end_date : '',
        ];
    }

    public function includeCouponBatch(Coupon $coupon)
    {
        return $this->item($coupon->couponBatch, new CouponBatchTransformer());
    }
}