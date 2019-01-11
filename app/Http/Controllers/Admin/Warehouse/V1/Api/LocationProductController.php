<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Location;
use App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\LocationProductTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationProductController extends Controller
{

    public function list(Request $request, LocationProduct $locationProduct)
    {
        $query = $locationProduct->query();
        //根据产品SKU查询
        if ($request->sku) {
            $query->where('product_sku', $request->sku);
        }

        //根据库位查询
        if ($request->location) {
            $query->where('location_id', $request->location);
        }

        //根据仓库查询
        if ($request->warehouse) {
            $warehouse = Location::query()->where('warehouse_id', $request->warehouse)->get()->toArray();
            $ids = array_column($warehouse, 'id');
            Location::query()->where('warehouse_id', $request->warehouse)->get();
            $query->whereIn('location_id', $ids);
        }

        $locationProduct = $query->paginate(10);
        return $this->response()->paginator($locationProduct, new LocationProductTransformer());
    }
}