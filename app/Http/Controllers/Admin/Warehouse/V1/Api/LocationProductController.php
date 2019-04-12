<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Location;
use App\Http\Controllers\Admin\Warehouse\V1\Models\LocationProduct;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\LocationProductTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationProductController extends Controller
{

    public function index(Request $request, LocationProduct $locationProduct)
    {
        $query = $locationProduct->query();
        //根据产品SKU查询,传入product_id
        if ($request->id) {
            $query->where('product_id', $request->id);
        }

        //根据库位查询
        if ($request->location) {
            $query->where('location_id', $request->location);
        }

        //根据仓库查询
        if ($request->warehouse) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('warehouse_id', $request->warehouse);
            });
        }

        $locationProduct = $query->orderByDesc('id')->paginate(10);
        return $this->response()->paginator($locationProduct, new LocationProductTransformer());
    }

    public function export(Request $request)
    {
        return excelExportByType($request, 'location_product');
    }

}