<?php

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Models\ContractProduct;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class ContractProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['contract'];

    public function transform(ContractProduct $contractProduct): array
    {
        return [
            'id' => $contractProduct->id,
            'contract_id' => $contractProduct->contract_id,
            'product_name' => $contractProduct->product_name,
            'product_color' => $contractProduct->product_color,
            'product_stock' => $contractProduct->product_stock,
        ];
    }

    public function includeContract(ContractProduct $contractProduct): Item
    {
        return $this->item($contractProduct->contract, new ContractTransformer());
    }
}