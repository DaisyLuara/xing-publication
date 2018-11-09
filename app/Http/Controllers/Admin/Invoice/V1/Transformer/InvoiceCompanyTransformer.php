<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 上午10:20
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Transformer;


use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany;
use League\Fractal\TransformerAbstract;

class InvoiceCompanyTransformer extends TransformerAbstract
{
    public function transform(InvoiceCompany $invoiceCompany)
    {
        return [
            'id' => $invoiceCompany->id,
            'name' => $invoiceCompany->name,
            'taxpayer_num' => $invoiceCompany->taxpayer_num,
            'phone' => $invoiceCompany->phone,
            'telephone' => $invoiceCompany->telephone,
            'address' => $invoiceCompany->address,
            'account_bank' => $invoiceCompany->account_bank,
            'account_number' => $invoiceCompany->account_number,
            'created_at' => $invoiceCompany->created_at->toDateString(),
            'updated_at' => $invoiceCompany->updated_at->toDateString()
        ];
    }
}