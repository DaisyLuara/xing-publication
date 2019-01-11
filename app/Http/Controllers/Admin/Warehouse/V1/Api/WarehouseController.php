<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse;
use App\Http\Controllers\Admin\Warehouse\V1\Request\WarehouseRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\WarehouseTransformer;

class WarehouseController extends Controller
{
    //单个仓库详情
    public function show(Warehouse $Warehouse)
    {
        return $this->response()->item($Warehouse, new WarehouseTransformer())->setStatusCode(200);
    }

    //仓库列表查询
    public function index(WarehouseRequest $request, Warehouse $warehouse)
    {
        $query = $warehouse->query();
        //根据仓库名称查询
        if ($request->name) {
            $name = $request->name;
            $query->where('name', 'like', '%' . $name . '%');
        }

        $warehouse = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($warehouse, new WarehouseTransformer())->setStatusCode(200);
    }

    //新增仓库
    public function store(WarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->fill($request->all())->saveOrFail();
        return $this->response->item($warehouse, new WarehouseTransformer());
    }

    //编辑仓库
    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->all());
        return $this->response()->item($warehouse, new WarehouseTransformer())->setStatusCode(200);
    }

}
