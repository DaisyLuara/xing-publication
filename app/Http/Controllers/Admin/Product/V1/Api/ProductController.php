<?php

namespace App\Http\Controllers\Admin\Product\V1\Api;

use App\Http\Controllers\Admin\Product\V1\Models\Product;
use App\Http\Controllers\Admin\Product\V1\Request\ProductRequest;
use App\Http\Controllers\Admin\Product\V1\Transformer\ProductTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function show(Product $product)
    {
        return $this->response->item($product, new ProductTransformer());
    }

    public function index(Request $request)
    {
        $products = Product::query()->paginate(10);
        return $this->response->paginator($products, new ProductTransformer());
    }

    public function store(ProductRequest $request)
    {
        $product = Product::query()->create($request->all());

        activity('create_product')
            ->causedBy($this->user())
            ->performedOn($product)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增产品');


        return $this->response->noContent();
    }

    public function update()
    {

    }
}