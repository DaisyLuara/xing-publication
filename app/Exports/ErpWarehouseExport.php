<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse;

class ErpWarehouseExport extends BaseExport
{

    private $name;//仓库名称

    public function __construct($request)
    {
        $this->name = $request->name;
        $this->fileName = '仓库-仓库管理列表';
    }

    public function collection()
    {
        $query = Warehouse::query();
        //根据仓库名称查询
        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }

        $warehouses = $query->orderByDesc('created_at')->get()
            ->map(function ($Warehouse) {
                return [
                    'id' => $Warehouse->id,
                    'name' => $Warehouse->name, //硬件型号
                    'address' => $Warehouse->address, //硬件颜色
                    'created_at' => $Warehouse->created_at->toDateTimeString(),
                    'updated_at' => $Warehouse->updated_at->toDateTimeString(),
                ];
            })->toArray();

        $header = ['ID', '仓库名称', '仓库地址', '创建时间', '最后操作时间'];

        $this->header_num = count($header);
        array_unshift($warehouses, $header, $header);
        $this->data = $data = collect($warehouses);

        return $data;
    }


}