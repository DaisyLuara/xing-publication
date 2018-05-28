<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\Area;
use League\Fractal\TransformerAbstract;

class AreaTransformer extends TransformerAbstract
{

    public function transform(Area $area)
    {
        return [
            'id' => (int)$area->areaid,
            'name' => $area->name,
        ];
    }
}