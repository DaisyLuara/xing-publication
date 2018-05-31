<?php

namespace App\Http\Controllers\Admin\Device\V1\Transformer;

use App\Http\Controllers\Admin\Ad\V1\Transformer\ProjectTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Device\V1\Models\ShortUrl;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class PushTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['point', 'project'];

    public function transform(ShortUrl $push)
    {
        return [
        ];
    }


}