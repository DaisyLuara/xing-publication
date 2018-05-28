<?php

namespace App\Http\Controllers\Admin\Project\V1\Api;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Admin\Project\V1\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $query = $product->query();
        $product = $query->paginate(10);
        return $this->response->paginator($product, new ProductTransformer());
    }
}