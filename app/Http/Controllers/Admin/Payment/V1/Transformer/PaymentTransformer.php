<?php

namespace App\Http\Controllers\Admin\Payment\V1\Transformer;

use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;

class PaymentTransformer extends TransformerAbstract
{
    protected $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '已付款',
        '5' => '驳回'
    ];

    protected $typeMapping = [
        '1' => '支票',
        '2' => '电汇单',
        '3' => '贷记凭证'
    ];
    protected $availableIncludes = ['contract', 'payment_payee'];

    public function transform(Payment $payment)
    {
        return [
            'id' => $payment->id,
            'contract_id' => $payment->contract->id,
            'contract_number' => $payment->contract->contract_number,
            'payment_payee_name' => $payment->paymentPayee ? $payment->paymentPayee->name : null,
            'applicant' => $payment->applicant,
            'applicant_name' => $payment->applicantUser->name,
            'amount' => $payment->amount,
            'type' => $this->typeMapping[$payment->type],
            'reason' => $payment->reason,
            'remark' => $payment->remark,
            'bd_ma_massage' => $payment->bd_ma_massage,
            'legal_message' => $payment->legal_message,
            'legal_ma_message' => $payment->legal_ma_message,
            'auditor_message' => $payment->auditor_message,
            'receive_status' => $payment->receive_status == 0 ? '未收票' : '已收票',
            'status' => $this->statusMapping[$payment->status],
            'handler' => $payment->handler,
            'handler_name' => $payment->handler ? $payment->handlerUser->name : null,
            'created_at' => $payment->created_at->toDateTimeString(),
            'updated_at' => $payment->updated_at->toDateTimeString()
        ];
    }

    public function includeContract(Payment $payment)
    {
        return $this->item($payment->contract, new ContractTransformer());
    }

    public function includePaymentPayee(Payment $payment)
    {
        if (!$payment->paymentPayee) {
            return null;
        }
        return $this->item($payment->paymentPayee, new PaymentPayeeTransformer());
    }
}