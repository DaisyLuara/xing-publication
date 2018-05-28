<?php

namespace App\Http\Controllers\Admin\Point\V1\Api;

use App\Http\Controllers\Admin\Point\V1\Transformer\PointConfigTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\PointConfig;
use App\Http\Controllers\Controller;

class PointConfigController extends Controller
{
    public function show(PointConfig $pointConfig)
    {
        return $this->response->item($pointConfig, new PointConfigTransformer());
    }
}
