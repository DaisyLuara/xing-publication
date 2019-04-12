<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Warehouse\V1\Models\WarehouseChange;

class ErpWarehouseChangeExport extends BaseExport
{

    private $id;//SKU
    private $out_location;//调出库位
    private $in_location;//调入库位

    public function __construct($request)
    {
        $this->id = $request->id;
        $this->out_location = $request->out_location;
        $this->in_location = $request->in_location;
        $this->fileName = '仓库-调拨记录列表';
    }

    public function collection()
    {
        $query = WarehouseChange::query();
        //根据sku查询
        if ($this->id) {
            $query->where('product_id', $this->id);
        }

        //根据调出库位查询
        if ($this->out_location) {
            $query->where('out_location', $this->out_location);
        }

        //根据调入库位查询
        if ($this->in_location) {
            $query->where('in_location', $this->in_location);
        }

        $warehouseChanges = $query->orderBy('created_at', 'desc')->get()
            ->map(function ($warehouseChange) {
                return [
                    'id' => $warehouseChange->id,
                    'sku' => $warehouseChange->product ? $warehouseChange->product->sku : '',
                    'out_location' => $warehouseChange->outLocation ? $warehouseChange->outLocation->name : '', //调出库位
                    'in_location' => $warehouseChange->inLocation ? $warehouseChange->inLocation->name : '', //调入库位
                    'num' => $warehouseChange->num,//调整数量
                    'remark' => $warehouseChange->remark,
                    'created_at' => $warehouseChange->created_at->toDateTimeString(),
                ];
            })->toArray();

        $header = ['ID', 'SKU', '调出库位', '调入库位', '调拨数量', '备注','时间'];

        $this->header_num = count($header);
        array_unshift($warehouseChanges, $header, $header);
        $this->data = $data = collect($warehouseChanges);

        return $data;
    }


}