<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;

class WecahtCouponBatchTransformer extends TransformerAbstract
{
    public function transform(WechatCouponBatch $wechatCouponBatch)
    {
        return [
            'id' => $wechatCouponBatch->id,
            'wechat_authorizer_id' => $wechatCouponBatch->wechat_authorizer_id,
            'card_id' => $wechatCouponBatch->card_id,
            'expire_at' => $wechatCouponBatch->expire_at,
        ];
    }
}