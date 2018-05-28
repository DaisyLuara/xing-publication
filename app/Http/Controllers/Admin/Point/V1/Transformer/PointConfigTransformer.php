<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\PointConfig;
use League\Fractal\TransformerAbstract;

class PointConfigTransformer extends TransformerAbstract
{

    public function transform(PointConfig $pointConfig)
    {
        return [
            'url' => (string)$pointConfig->url,
        ];
    }
}
