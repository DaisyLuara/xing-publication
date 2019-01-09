<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;


use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\LocationProductTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LocationProductController extends Controller
{

    public function list(Request $request, LocationProduct $locationProduct)
    {
        /** @var  $user \App\Models\User */
//        $user = $this->user();

        $query = $locationProduct->query();
        //根据产品SKU查询
        if ($request->sku) {
            $query->where('sku', $request->sku);
        }

        $locationProduct = $query->paginate(10);
        return $this->response()->paginator($locationProduct, new LocationProductTransformer());
    }
}