<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Request\ProductAttributeRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ProductAttributeTransformer;
use App\Http\Controllers\Controller;


class ProductAttributeController extends Controller
{
    //根据产品SKU，查出对应产品属性
    public function index(ProductAttributeRequest $request, ProductAttribute $productAttribute)
    {
        $query = $productAttribute->query();
        //根据产品SKU查询
        if ($request->sku) {
            $query->where('product_sku', $request->sku);
        }
        $product = $query->orderBy('attributes_id', 'asc')->paginate(10);
        return $this->response()->paginator($product, new ProductAttributeTransformer())->setStatusCode(200);
    }

}
