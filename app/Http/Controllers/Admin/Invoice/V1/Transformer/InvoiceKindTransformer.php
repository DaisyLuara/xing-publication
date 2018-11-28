<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/28
 * Time: 上午11:27
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Transformer;


use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind;
use League\Fractal\TransformerAbstract;

class InvoiceKindTransformer extends TransformerAbstract
{
    public function transform(InvoiceKind $invoiceKind)
    {
        return [
            'id' => $invoiceKind->id,
            'name' => $invoiceKind->name
        ];
    }

}