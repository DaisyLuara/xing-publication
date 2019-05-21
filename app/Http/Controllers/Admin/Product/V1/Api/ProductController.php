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
        Product::query()->create($request->all());
        return $this->response->noContent();
    }

    public function update()
    {

    }
}