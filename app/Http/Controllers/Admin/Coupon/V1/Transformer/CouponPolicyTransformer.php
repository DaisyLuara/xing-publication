<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Transformer;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponPolicy;
use League\Fractal\TransformerAbstract;

class CouponPolicyTransformer extends TransformerAbstract
{
    public function transform(CouponPolicy $couponPolicy){
        return [
            'id'=>$couponPolicy->id,
            'age'=>$couponPolicy->age,
            'sex'=>$couponPolicy->sex,
            'chance'=>$couponPolicy->chance,
        ];
    }
}