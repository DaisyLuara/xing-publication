<?php

namespace App\Http\Controllers\Admin\Common\V3\Transformer;

use App\Http\Controllers\Admin\Common\V3\Models\Confession;
use League\Fractal\TransformerAbstract;

class ConfessionTransformer extends TransformerAbstract
{

    public function transform(Confession $confession)
    {
        return [
            'id' => $confession->id,
            'name' => $confession->name,
            'phone' => $confession->phone,
            'message' => $confession->message,
            'qiniu_id' => $confession->qiniu_id,
            'record_id' => $confession->record_id,
            'utm_campaign' => $confession->utm_campaign,
            'url' => $confession->qiniu_id ? $confession->fileUpload->image : $confession->media->url,
            'created_at' => $confession->created_at->toDateTimeString(),
            'updated_at' => $confession->updated_at->toDateTimeString(),
        ];
    }
}