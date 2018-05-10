<?php

namespace App\Transformers;

use App\Models\Area;
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