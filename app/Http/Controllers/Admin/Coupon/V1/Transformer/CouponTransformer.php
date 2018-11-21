<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Company\V1\Transformer\CustomerTransformer;

class CouponTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['couponBatch', 'point','customer'];

    public function transform(Coupon $coupon)
    {
        return [
            'code' => $coupon->code,
            'name' => $coupon->couponBatch->name,
            'status' => (int)$coupon->status,
            'mobile' => $coupon->mobile,
            'wx_user_id' => $coupon->wx_user_id,
            'taobao_user_id' => $coupon->taobao_user_id,
            'created_at' => $coupon->created_at->toDateTimeString(),
            'updated_at' => $coupon->updated_at->toDateTimeString(),
        ];
    }

    public function includeCouponBatch(Coupon $coupon)
    {
        return $this->item($coupon->couponBatch, new CouponBatchTransformer());
    }

    public function includePoint(Coupon $coupon)
    {
        if (!$coupon->point) {
            return null;
        }
        return $this->item($coupon->point, new PointTransformer());
    }

    public function includeCustomer(Coupon $coupon)
    {
        if (!$coupon->customer) {
            return null;
        }
        return $this->item($coupon->customer, new CustomerTransformer());
    }
}