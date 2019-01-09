<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\ProductChuchang;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ProductChuchangTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductChuchangController extends Controller
{
    public function index(Request $request, ProductChuchang $productChuchang)
    {
        $query = $productChuchang->query();

        if ($request->id) {
            $query->where('contract_id', $request->id);
        }

        $productChuchang = $query->paginate(10);
        return $this->response()->paginator($productChuchang, new ProductChuchangTransformer());
    }
}