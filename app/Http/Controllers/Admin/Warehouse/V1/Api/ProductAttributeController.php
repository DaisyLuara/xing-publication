<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Product;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Request\ProductAttributeRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ProductAttributeTransformer;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ProductTransformer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;


class ProductAttributeController extends Controller
{
    //产品详情
    public function show(Product $product)
    {
        return $this->response()->item($product, new ProductTransformer())->setStatusCode(200);
    }

    //根据产品SKU，查出对应产品属性
    public function index(ProductAttributeRequest $request, ProductAttribute $productAttribute)
    {

//        dd('1111')
        $query = $productAttribute->query();


        //根据产品SKU查询
        if ($request->sku) {
            $query->where('product_sku', $request->sku);
        }

//        //根据供应商查询
//        if ($request->supplier) {
//            $query->where('supplier', $request->supplier);
//        }


        $product = $query->orderBy('attributes_id', 'asc')->paginate(10);
        return $this->response()->paginator($product, new ProductAttributeTransformer())->setStatusCode(200);
    }

    //新增产品
    public function store(ProductRequest $request, Product $product)
    {
        $productData = [
            'sku' => $request->sku,
            'supplier' => $request->supplier
        ];
        $product->fill(array_merge($productData));
        $product->save();


        $param = $request->all();

        $attributeData = $param['attribute'];

dd($attributeData);
        foreach ($attributeData as $item) {
            $item['product_sku'] = $request->sku;
            dd($item);
            ProductAttribute::query()->create($item);
        }



//
//        return $this->response->item($company, new CompanyTransformer())
//            ->setStatusCode(201);




        $product->fill($request->all())->saveOrFail();

        return $this->response->item($product, new ProductTransformer());
    }

    //编辑产品
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->all());
        return $this->response()->item($product, new ProductTransformer())->setStatusCode(200);
    }

}
