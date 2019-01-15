<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Attribute;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Product;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Request\ProductAttributeRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ProductAttributeTransformer;
use App\Http\Controllers\Controller;


class ProductAttributeController extends Controller
{
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

    //根据产品SKU，查出对应产品属性,传参改为product_id
    public function list(ProductAttributeRequest $request, ProductAttribute $productAttribute)
    {
        $query = $productAttribute->query();
        //根据产品SKU查询
        if ($request->id) {
            $query->where('product_id', $request->id);
        }
        $product = $query->select('attributes_id', 'attributes_value')->orderBy('attributes_id', 'asc')->get()->toArray();

        $supplier = Product::query()->where('id', $request->id)->get(['supplier'])->toArray();

        $company = Company::query()->where('id',$supplier)->select('name', 'internal_name')->get()->toArray();

        $arr = [];
        foreach ($product as $value){
            $arr['supplier'] = $company;
            $attribute = Attribute::query()->where('id', $value['attributes_id'])->select('name')->get()->toArray();
            $arr[$attribute[0]['name']] = $value;
        }

        return $arr;
    }
}
