<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Transformer;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use League\Fractal\TransformerAbstract;

class CouponPackTransformer extends TransformerAbstract
{
    public function transform(CouponBatch $couponBatch)
    {
        return [
            'name' => $couponBatch->name,
            'description' => $couponBatch->description,
        ];
    }
}