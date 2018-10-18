<?php

namespace App\Http\Controllers\Admin\Payment\V1\Transformer;

use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract
{
    public function transform(Payment $payment){
        return [
            'id'=>$payment->id,
            'contract_number'=>$payment->contract->contract_number,
            'payee'=>$payment->payee,
            'applicant'=>$payment->applicant,
            'amount'=>$payment->amount,
            'type'=>$payment->type,
            'remark'=>$payment->remark,
            'account_bank'=>$payment->account_bank,
            'account_number'=>$payment->account_number,
            'receive_status'=>$payment->receive_status,
            'status'=>$payment->status,
            'handler'=>$payment->handler,
            'created_at'=>$payment->created_at,
            'updated_at'=>$payment->updated_at
        ];
    }
}