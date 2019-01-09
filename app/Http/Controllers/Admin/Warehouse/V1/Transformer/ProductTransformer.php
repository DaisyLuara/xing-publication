<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;


use App\Http\Controllers\Admin\Warehouse\V1\Models\Product;
use League\Fractal\TransformerAbstract;


class ProductTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['attributes'];

    public function transform(Product $product)
    {

        return [
            'id' => $product->id,
            'sku' => $product->sku, //硬件型号
            'supplier' => $product->supplier,//供应商ID
            'supplier_name' => $product->company->name,//供应商
            'created_at' => $product->created_at->toDateTimeString(),
            'updated_at' => $product->updated_at->toDateTimeString(),
        ];
    }

    public function includeCompany(Product $product)
    {
        return $this->collection($product->media, new CompanyTransformer());
    }

    public function includeAttributes(Product $product)
    {
        return $this->collection($product->attributes, new ProductAttributeTransformer());
    }

}