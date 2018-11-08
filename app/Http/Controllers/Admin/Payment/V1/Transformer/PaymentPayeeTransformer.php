<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 下午2:35
 */

namespace App\Http\Controllers\Admin\Payment\V1\Transformer;


use App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee;
use League\Fractal\TransformerAbstract;

class PaymentPayeeTransformer extends TransformerAbstract
{
    public function transform(PaymentPayee $paymentPayee)
    {
        return [
            'id' => $paymentPayee->id,
            'name' => $paymentPayee->name,
            'account_bank' => $paymentPayee->account_bank,
            'account_number' => $paymentPayee->account_number,
            'created_at' => $paymentPayee->created_at,
            'updated_at' => $paymentPayee->updated_at
        ];
    }
}