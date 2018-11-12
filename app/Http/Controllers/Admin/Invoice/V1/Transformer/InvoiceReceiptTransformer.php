<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/12
 * Time: 下午2:16
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Transformer;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractReceiveDateTransformer;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt;
use League\Fractal\TransformerAbstract;

class InvoiceReceiptTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['receive_date'];

    public function transform(InvoiceReceipt $invoiceReceipt)
    {
        return [
            'id' => $invoiceReceipt->id,
            'receipt_company' => $invoiceReceipt->receipt_company,
            'receipt_date' => $invoiceReceipt->receipt_date,
            'claim_status' => $invoiceReceipt->claim_status,
        ];
    }

    public function includeReceiveDate(InvoiceReceipt $invoiceReceipt)
    {
        return $this->item($invoiceReceipt->receiveDate(), new ContractReceiveDateTransformer());
    }

}