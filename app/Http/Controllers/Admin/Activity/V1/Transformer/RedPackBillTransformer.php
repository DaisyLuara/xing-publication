<?php

namespace App\Http\Controllers\Admin\Activity\V1\Transformer;

use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill;
use League\Fractal\TransformerAbstract;

class RedPackBillTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['couponBatch'];

    public function transform(RedPackBill $redPackBill)
    {
        return [
            'id' => $redPackBill->id,
            'coupon_code' => $redPackBill->coupon_code,
            'mch_billno' => $redPackBill->mch_billno,
            're_openid' => $redPackBill->re_openid,
            'total_amount' => $redPackBill->total_amount,
            'scene_id' => $redPackBill->scene_id,
            'return_code' => $redPackBill->return_code,
            'result_code' => $redPackBill->result_code,
            'err_code_des' => $redPackBill->err_code_des,
            'remark' => $redPackBill->remark,
            'send_name' => $redPackBill->send_name,
            'created_at' => $redPackBill->created_at->toDateTimeString(),
            'updated_at' => $redPackBill->updated_at->toDateTimeString(),
        ];
    }

    public function includeCouponBatch(RedPackBill $redPackBill)
    {
        $couponBatch = $redPackBill->couponBatch;
        if ($couponBatch) {
            return $this->item($couponBatch, new CouponBatchTransformer());
        }
    }


}