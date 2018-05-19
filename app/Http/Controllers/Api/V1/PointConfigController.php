<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PointConfig;
use App\Transformers\PointConfigTransformer;

class PointConfigController extends Controller
{
    public function show(PointConfig $pointConfig)
    {
        return $this->response->item($pointConfig, new PointConfigTransformer());
    }
}
