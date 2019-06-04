<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Api;


use App\Http\Controllers\Admin\Warehouse\V1\Models\Location;
use App\Http\Controllers\Admin\Warehouse\V1\Request\LocationRequest;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\LocationTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class LocationController extends Controller
{
    //单个库位详情
    public function show(Location $location)
    {
        return $this->response()->item($location, new LocationTransformer())->setStatusCode(200);
    }

    //库位查询
    public function index(LocationRequest $request, Location $location)
    {
        $query = $location->query();
        //根据库位名称查询
        if ($request->name) {
            $name = $request->name;
            $query->where('name', 'like', '%' . $name . '%');
        }

        $Location = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($Location, new LocationTransformer())->setStatusCode(200);
    }

    //新增库位
    public function store(LocationRequest $request, Location $location)
    {
        $location->fill($request->all())->saveOrFail();

        activity('create_location')
            ->causedBy($this->user())
            ->performedOn($location)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增库位');

        return $this->response->item($location, new LocationTransformer());
    }

    //编辑库位
    public function update(LocationRequest $request, Location $location)
    {
        $location->update($request->all());

        activity('update_location')
            ->causedBy($this->user())
            ->performedOn($location)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑库位');

        return $this->response()->item($location, new LocationTransformer())->setStatusCode(200);
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'erp_location');
    }

}
