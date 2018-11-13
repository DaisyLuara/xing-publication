<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany;
use League\Fractal\TransformerAbstract;

class InvoiceTransformer extends TransformerAbstract
{
    protected $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '已开票',
        '5' => '已认领',
        '6' => '驳回',
    ];
    protected $availableIncludes = ['invoice_content', 'contract', 'invoice_company'];

    public function transform(Invoice $invoice)
    {
        return [
            'id' => $invoice->id,
            'contract_id' => $invoice->contract_id,
            'contract_number' => $invoice->contract->contract_number,
            'company_name' => $invoice->contract->company->name,
            'applicant' => $invoice->applicant,
            'applicant_name' => $invoice->applicantUser->name,
            'handler' => $invoice->handler,
            'handler_name' => $invoice->handler ? $invoice->handlerUser->name : null,
            'type' => $invoice->type == 0 ? '专票' : '普票',
            'invoice_company_name' => $invoice->invoiceCompany ? $invoice->invoiceCompany->name : null,
            'status' => $this->statusMapping[$invoice->status],
            'kind' => $invoice->kind,
            'total' => $invoice->total,
            '$total_text' => $invoice->total_text,
            'remark' => $invoice->remark,
            'bd_ma_message' => $invoice->bd_ma_message,
            'legal_ma_message' => $invoice->legal_ma_message,
            'created_at' => $invoice->created_at->toDateTimeString(),
            'updated_at' => $invoice->updated_at->toDateTimeString()
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

    public function includeInvoiceCompany(Invoice $invoice)
    {
        if (!$invoice->invoiceCompany) {
            return null;
        }
        return $this->item($invoice->invoiceCompany, new InvoiceCompanyTransformer());
    }
}