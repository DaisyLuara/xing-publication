<?php

namespace App\Http\Controllers\Admin\Payment\V1\Transformer;

use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract
{
    protected $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '已付款'
    ];

    protected $typeMapping = [
        '1' => '支票',
        '2' => '电汇单',
        '3' => '贷记凭证'
    ];

    public function transform(Payment $payment)
    {
        return [
            'id' => $payment->id,
            'contract_number' => $payment->contract->contract_number,
            'payee' => $payment->payee,
            'applicant'=>$payment->applicant,
            'applicant_name' => $payment->applicantUser->name,
            'amount' => $payment->amount,
            'type' => $this->typeMapping[$payment->type],
            'remark' => $payment->remark,
            'account_bank' => $payment->account_bank,
            'account_number' => $payment->account_number,
            'receive_status' => $payment->receive_status == 0 ? '未收票' : '已收票',
            'status' => $this->statusMapping[$payment->status],
            'handler' => $payment->handler,
            'handler_name' => $payment->handler? $payment->handlerUser->name:null,
            'created_at' => $payment->created_at->toDateTimeString(),
            'updated_at' => $payment->updated_at->toDateTimeString()
        ];
    }
}