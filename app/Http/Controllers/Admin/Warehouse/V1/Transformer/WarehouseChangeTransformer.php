<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\WareHouse\V1\Models\WareHouseChange;
use League\Fractal\TransformerAbstract;


class WarehouseChangeTransformer extends TransformerAbstract
{
    public function transform(WarehouseChange $warehouseChange)
    {
        return [
            'id' => $warehouseChange->id,
            'sku' => isset($warehouseChange->product->sku) ? $warehouseChange->product->sku : '',
            'product_id' =>isset($warehouseChange->product->id)?$warehouseChange->product->id : '',
            'out_location' => isset($warehouseChange->outLocation->name) ? $warehouseChange->outLocation->name : '', //调出库位
            'in_location' => isset($warehouseChange->inLocation->name) ? $warehouseChange->inLocation->name : '', //调入库位
            'num' => $warehouseChange->num,//调整数量
            'remark' => $warehouseChange->remark,
            'created_at' => $warehouseChange->created_at->toDateTimeString(),
            'updated_at' => $warehouseChange->updated_at->toDateTimeString(),
        ];
    }
}