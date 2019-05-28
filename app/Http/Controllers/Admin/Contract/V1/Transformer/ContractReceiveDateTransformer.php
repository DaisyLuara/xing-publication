<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/24
 * Time: 下午2:15
 */

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceReceiptTransformer;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class ContractReceiveDateTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['invoiceReceipt', 'contract'];

    public function transform(ContractReceiveDate $contractReceiveDate): array
    {
        return [
            'id' => $contractReceiveDate->id,
            'contract_id' => $contractReceiveDate->id,
            'receive_date' => $contractReceiveDate->receive_date,
            'receive_status' => $contractReceiveDate->receive_status === 0 ? '未收款' : '已收款',
        ];
    }

    public function includeInvoiceReceipt(ContractReceiveDate $contractReceiveDate)
    {
        if (!$contractReceiveDate->invoiceReceipt) {
            return null;
        }
        return $this->item($contractReceiveDate->invoiceReceipt, new InvoiceReceiptTransformer());
    }

    public function includeContract(ContractReceiveDate $contractReceiveDate): Item
    {
        return $this->item($contractReceiveDate->contract, new ContractTransformer());
    }
}