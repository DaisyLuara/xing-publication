<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Transformer;

use App\Http\Controllers\Admin\Warehouse\V1\Models\Location;
use League\Fractal\TransformerAbstract;


class LocationTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['hardware'];

    public function transform(Location $location)
    {

        return [
            'id' => $location->id,
            'name' => $location->name, //库位名称
            'warehouse' => $location->warehouse->name, //对应仓库名称
            'warehouse_id' => (int)$location->warehouse_id, //对应仓库ID
            'created_at' => $location->created_at->toDateTimeString(),
            'updated_at' => $location->updated_at->toDateTimeString(),
        ];
    }
}