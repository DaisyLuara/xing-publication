<?php

namespace App\Http\Controllers\Admin\Media\V1\Transformer;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{
    public function transform(Media $media)
    {
        $disk = \Storage::disk('qiniu');
        $domain = $disk->getDriver()->downloadUrl();
        return [
            'id' => $media->id,
            'company_id' => $media->company_id,
            'contract_id' => $media->contract_id,
            'name' => $media->name,
            'type' => $media->type,
            'size' => $media->size,
            'url' => $media->type == 'package' ? $domain . $media->url : $media->url,
            'height' => $media->height,
            'width' => $media->width,
            'created_at' => $media->created_at->toDateTimeString(),
            'updated_at' => $media->updated_at->toDateTimeString(),
        ];
    }
}