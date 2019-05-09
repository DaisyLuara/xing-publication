<?php

namespace App\Http\Controllers\Admin\Common\V3\Transformer;

use App\Http\Controllers\Admin\Common\V3\Models\Confession;
use League\Fractal\TransformerAbstract;

class ConfessionTransformer extends TransformerAbstract
{

    public function transform(Confession $confession)
    {
        return [
            'name' => $confession->name,
            'phone' => $confession->phone,
            'message' => $confession->message,
            'url' => $confession->url,
            'created_at' => $confession->created_at->toDateTimeString(),
            'updated_at' => $confession->updated_at->toDateTimeString(),
        ];
    }
}