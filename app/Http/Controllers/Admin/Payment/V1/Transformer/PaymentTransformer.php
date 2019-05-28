<?php

namespace App\Http\Controllers\Admin\Payment\V1\Transformer;

use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;

class PaymentTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['contract', 'payment_payee', 'media'];

    public function transform(Payment $payment): array
    {
        return [
            'id' => $payment->id,
            'contract_id' => $payment->contract->id,
            'contract_number' => $payment->contract->contract_number,
            'payment_payee_name' => $payment->paymentPayee ? $payment->paymentPayee->name : null,
            'applicant' => $payment->applicant,
            'applicant_name' => $payment->applicantUser->name,
            'owner' => $payment->owner,
            'owner_name' => $payment->owner,
            'amount' => $payment->amount,
            'type' => Payment::$typeMapping[$payment->type],
            'reason' => $payment->reason,
            'remark' => $payment->remark,
            'bd_ma_message' => $payment->bd_ma_message,
            'legal_message' => $payment->legal_message,
            'legal_ma_message' => $payment->legal_ma_message,
            'auditor_message' => $payment->auditor_message,
            'payer' => $payment->payer,
            'receive_status' => $payment->receive_status === 0 ? '未收票' : '已收票',
            'status' => Payment::$statusMapping[$payment->status],
            'handler' => $payment->handler,
            'handler_name' => $payment->handler ? $payment->handlerUser->name : null,
            'created_at' => $payment->created_at->toDateTimeString(),
            'updated_at' => $payment->updated_at->toDateTimeString()
        ];
    }

    public function includeContract(Payment $payment): Item
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

    public function includeMedia(Payment $payment): Collection
    {
        return $this->collection($payment->media, new MediaTransformer());
    }
}