<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use League\Fractal\TransformerAbstract;

class InvoiceTransformer extends TransformerAbstract
{
    protected $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已开票',
        '4' => '已认领'
    ];
    protected $availableIncludes = ['invoice_content', 'contract'];

    public function transform(Invoice $invoice)
    {
        return [
            'id' => $invoice->id,
            'contract_id' => $invoice->contract_id,
            'contract_number' => $invoice->contract->contract_number,
            'company_name' => $invoice->contract->company->name,
            'applicant' => $invoice->applicant,
            'handler' => $invoice->handler,
            'handler_name'=>$invoice->handlerUser->name,
            'type' => $invoice->type == 0 ? '专票' : '普票',
            'taxpayer_num' => $invoice->taxpayer_num,
            'phone' => $invoice->phone,
            'address' => $invoice->address,
            'account_bank' => $invoice->account_bank,
            'account_number' => $invoice->account_number,
            'status' => $this->statusMapping[$invoice->status],
            'receive_status' => $invoice->receive_status == 0 ? '未收款' : '已收款',
            'kind' => $invoice->kind,
            'total' => $invoice->total,
            'remark' => $invoice->remark,
            'created_at' => $invoice->created_at,
            'updated_at' => $invoice->updated_at
        ];
    }

    public function includeInvoiceContent(Invoice $invoice)
    {
        return $this->collection($invoice->invoiceContent, new InvoiceContentTransformer());
    }

    public function includeContract(Invoice $invoice)
    {
        return $this->item($invoice->contract, new ContractTransformer());
    }
}