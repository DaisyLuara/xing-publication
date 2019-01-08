<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Location;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use League\Fractal\TransformerAbstract;
use App\Http\Controllers\Admin\Contract\V1\Models\HardwareChange;


class LocationTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['hardware'];

    public function transform(Location $location)
    {

        return [
            'id' => $location->id,
            'name' => $location->name, //库位名称
            'warehouse' => $location->warehouse->name, //对应仓库ID
            'created_at' => $location->created_at->toDateTimeString(),
            'updated_at' => $location->updated_at->toDateTimeString(),
        ];
    }

    public function includeMedia(Contract $contract)
    {
        return $this->collection($contract->media, new MediaTransformer());
    }

    public function includeReceiveDate(Contract $contract)
    {
        return $this->collection($contract->receiveDate, new ContractReceiveDateTransformer());
    }

    public function includeHardware(HardwareChange $hardwareChange)
    {
        if($hardwareChange->hardware_id){
            return $this->item($hardwareChange->hardware, new ProductTransformer());
        }

    }
}