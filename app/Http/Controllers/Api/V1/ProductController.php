<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/17
 * Time: 20:23
 */

namespace App\Http\Controllers\Api\V1;


use App\Models\Product;
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