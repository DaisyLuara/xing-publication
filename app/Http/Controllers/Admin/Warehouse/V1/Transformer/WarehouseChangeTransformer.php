<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\WareHouse\V1\Models\WareHouseChange;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ProductTransformer;
use GuzzleHttp\Promise\RejectionException;
use League\Fractal\TransformerAbstract;
use Illuminate\Http\Request;
use DB;


class WarehouseChangeTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['hardware'];

    public function transform(WarehouseChange $warehouseChange)
    {
        return [
            'id' => $warehouseChange->id,
            'sku' => $warehouseChange->sku,
            'out_location' => $warehouseChange->out_location, //调出库位
            'in_location' => $warehouseChange->in_location, //调入库位
            'num' => $warehouseChange->num,//调整数量
            'remark' => $warehouseChange->remark,
            'created_at' => $warehouseChange->created_at->toDateTimeString(),
            'updated_at' => $warehouseChange->updated_at->toDateTimeString(),
        ];
    }

    //查出对应硬件信息的总库存
    public function includeHardware(HardwareChange $hardwareChange)
    {
        return $this->item($hardwareChange->hardware, new ProductTransformer());
    }

}