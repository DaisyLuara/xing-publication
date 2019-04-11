<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Location;

class ErpLocationExport extends BaseExport
{

    private $name;//库位名称

    public function __construct($request)
    {
        $this->name = $request->name;
        $this->fileName = '票据-库位管理列表';
    }

    public function collection()
    {

        $query = Location::query();
        //根据库位名称查询
        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }

        $Locations = $query->orderByDesc('created_at')->get()
            ->map(function ($location) {
                return [
                    'id' => $location->id,
                    'name' => $location->name, //库位名称
                    'warehouse' => isset($location->warehouse->name) ? $location->warehouse->name : '', //对应仓库名称
                    'created_at' => $location->created_at->toDateTimeString(),
                    'updated_at' => $location->updated_at->toDateTimeString(),
                ];
            })->toArray();

        $header = ['ID', '库位', '所属仓库', '创建时间', '最后操作时间'];

        $this->header_num = count($header);
        array_unshift($Locations, $header, $header);
        $this->data = $data = collect($Locations);

        return $data;
    }


}