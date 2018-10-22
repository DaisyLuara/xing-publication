<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/16
 * Time: 下午2:11
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Transformer;


use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceContent;
use League\Fractal\TransformerAbstract;

class InvoiceContentTransformer extends TransformerAbstract
{
    public function transform(InvoiceContent $invoiceContent)
    {
        return [
            'name' => $invoiceContent->name,
            'spec_type' => $invoiceContent->spec_type,
            'unit' => $invoiceContent->unit,
            'num' => $invoiceContent->num,
            'price' => $invoiceContent->price,
            'money' => $invoiceContent->money,
        ];
    }
}