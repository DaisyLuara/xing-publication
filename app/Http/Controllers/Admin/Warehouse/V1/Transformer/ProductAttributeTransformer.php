<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Product;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Contract\V1\Models\HardwareChange;
use Illuminate\Http\Request;

class ProductAttributeTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['attributes'];

    public function transform(ProductAttribute $productAttribute)
    {
//
//        dd($productAttribute->attribute);
        return [
            'id' =>  $productAttribute->id,
            'sku' => $productAttribute->product_sku, //硬件型号
//            'supplier' => $product->company->name,//供应商
        'attributes_id' => $productAttribute->attributes_id,
            'attributes_name' => $productAttribute->attribute->display_name,
            'attributes_value' => $productAttribute->attributes_value,
//            'created_at' => $productAttribute->created_at->toDateTimeString(),
//            'updated_at' => $productAttribute->updated_at->toDateTimeString(),
        ];
    }

    public function includeCompany(Product $product)
    {
        return $this->collection($product->media, new CompanyTransformer());
    }

    public function includeAttributes(ProductAttribute $productAttribute)
    {
        return $this->collection($productAttribute->attributes, new ProductAttributeTransformer());
    }

}