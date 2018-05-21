<?php

namespace App\Transformers;

use App\Models\PointConfig;
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
