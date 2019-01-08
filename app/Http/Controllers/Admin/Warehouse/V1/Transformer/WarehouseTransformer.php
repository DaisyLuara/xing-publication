<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse;
use League\Fractal\TransformerAbstract;


class WarehouseTransformer extends TransformerAbstract
{

    public function transform(Warehouse $Warehouse)
    {
        return [
            'id' => $Warehouse->id,
            'name' => $Warehouse->name, //硬件型号
            'address' => $Warehouse->address, //硬件颜色
            'created_at' => $Warehouse->created_at->toDateTimeString(),
            'updated_at' => $Warehouse->updated_at->toDateTimeString(),
        ];
    }

}