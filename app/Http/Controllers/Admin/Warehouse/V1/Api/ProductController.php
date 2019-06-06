<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Product;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Request\ProductRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ProductTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


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
        //根据产品SKU查询,传入product_id
        if ($request->id) {
            $query->where('id', $request->id);
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
        $product->fill($productData);
        $product->saveOrFail();

        $param = $request->all();
        $attributeData = $param['attribute'];

        foreach ($attributeData as $item) {
            $item['product_id'] = $product->id;
            ProductAttribute::query()->create($item);
        }

        activity('create_erp_product')
            ->causedBy($this->user())
            ->performedOn($product)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增Erp产品');

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
            //找出属性表对应prodduct_id,更新属性值
            ProductAttribute::query()->where('product_id', $request->id)
                ->where('attributes_id', $item['attributes_id'])->update($item);
        }

        activity('update_erp_product')
            ->causedBy($this->user())
            ->performedOn($product)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑Erp产品');

        return $this->response()->item($product, new ProductTransformer())->setStatusCode(200);
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'erp_product');
    }

}
