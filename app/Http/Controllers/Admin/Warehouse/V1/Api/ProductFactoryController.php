<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductFactory;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ProductFactoryTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductFactoryController extends Controller
{
    public function index(Request $request, ProductFactory $productFactory)
    {
        $query = $productFactory->query();

        if ($request->id) {
            $query->where('contract_id', $request->id);
        }
        $productFactory = $query->get();
        return $this->response()->collection($productFactory, new ProductFactoryTransformer())->setStatusCode(200);
    }
}