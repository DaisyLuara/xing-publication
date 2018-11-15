<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/12
 * Time: 下午2:16
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Transformer;


use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractReceiveDateTransformer;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt;
use League\Fractal\TransformerAbstract;

class InvoiceReceiptTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['receiveDate'];

    public function transform(InvoiceReceipt $invoiceReceipt)
    {
        return [
            'id' => $invoiceReceipt->id,
            'receipt_company' => $invoiceReceipt->receipt_company,
            'receipt_money' => $invoiceReceipt->receipt_money,
            'receipt_date' => $invoiceReceipt->receipt_date,
            'claim_status' => $invoiceReceipt->claim_status == 0 ? '未认领' : '已认领',
            'creator'=>$invoiceReceipt->creator,
        ];
    }

    public function includeReceiveDate(InvoiceReceipt $invoiceReceipt)
    {
        if (!$invoiceReceipt->receiveDate) {
            return null;
        }
        return $this->item($invoiceReceipt->receiveDate, new ContractReceiveDateTransformer());
    }

}