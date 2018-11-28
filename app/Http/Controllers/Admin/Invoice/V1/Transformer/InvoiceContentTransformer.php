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
    protected $availableIncludes = ['invoiceKind', 'goodsService'];

    public function transform(InvoiceContent $invoiceContent)
    {
        return [
            'num' => $invoiceContent->num,
            'price' => $invoiceContent->price,
            'money' => $invoiceContent->money,
        ];
    }

    public function includeInvoiceKind(InvoiceContent $invoiceContent)
    {
        return $this->item($invoiceContent->invoiceKind, new InvoiceKindTransformer());
    }

    public function includeGoodsService(InvoiceContent $invoiceContent)
    {
        return $this->item($invoiceContent->goodsService, new GoodsServiceTransformer());
    }
}