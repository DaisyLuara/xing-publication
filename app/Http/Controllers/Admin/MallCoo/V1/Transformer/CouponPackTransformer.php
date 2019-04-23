<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Transformer;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use League\Fractal\TransformerAbstract;

class CouponPackTransformer extends TransformerAbstract
{
    public function transform(CouponBatch $couponBatch)
    {
        return [
            'id' => $couponBatch->id,
            'name' => $couponBatch->name,
            'description' => $couponBatch->description,
            'image_url' => preg_replace('/exe666.com/','xingstation.cn', $couponBatch->image_url),
            'bs_image_url' => preg_replace('/exe666.com/','xingstation.cn', $couponBatch->bs_image_url),
        ];
    }
}