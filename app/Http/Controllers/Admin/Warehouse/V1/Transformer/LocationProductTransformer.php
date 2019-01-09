<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceReceiptTransformer;
use League\Fractal\TransformerAbstract;

class LocationProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['invoiceReceipt', 'contract'];

    public function transform(LocationProduct $locationProduct)
    {
        return [
            'id' => $locationProduct->id,
            'sku' =>$locationProduct->product_sku,
            'location' => $locationProduct->location,
            'product' => $locationProduct->product,
            'stock' => $locationProduct->stock,
        ];
    }


    public function includeContract(ContractReceiveDate $contractReceiveDate)
    {
        return $this->item($contractReceiveDate->contract, new ContractTransformer());
    }
}