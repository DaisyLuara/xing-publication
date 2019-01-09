<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Product;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Request\ProductRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ProductTransformer;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{
    //产品详情
    public function show(Product $product)
    {
        return $this->response()->item($product, new ProductTransformer())->setStatusCode(200);
    }

    //产品查询
    public function index(ProductRequest $request, Product $product)
    {
        $query = $product->query();
        //根据产品SKU查询
        if ($request->sku) {
            $query->where('sku', $request->sku);
        }
        //根据供应商查询
        if ($request->supplier) {
            $query->where('supplier', $request->supplier);
        }

        $product = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($product, new ProductTransformer())->setStatusCode(200);
    }

    //新增产品
    public function store(ProductRequest $request, Product $product)
    {
        $productData = [
            'sku' => $request->sku,
            'supplier' => $request->supplier
        ];
        $product->fill(array_merge($productData));
        $product->saveOrFail();

        $param = $request->all();
        $attributeData = $param['attribute'];

        foreach ($attributeData as $item) {
            $item['product_sku'] = $request->sku;
            ProductAttribute::query()->create($item);
        }
        return $this->response->item($product, new ProductTransformer());
    }

    //编辑产品
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->all());

        $param = $request->all();
        $attributeData = $param['attribute'];

        foreach ($attributeData as $item) {
           //找出属性表对应SKU，更新属性值
            ProductAttribute::query()->where([['product_sku', '=', $request->sku], ['attributes_id', '=', $item['attributes_id']]])->update($item);
        }
        return $this->response()->item($product, new ProductTransformer())->setStatusCode(200);
    }

}
