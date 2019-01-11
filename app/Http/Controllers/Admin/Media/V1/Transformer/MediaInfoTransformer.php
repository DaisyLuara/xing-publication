<?php

namespace App\Http\Controllers\Admin\Media\V1\Transformer;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Media\V1\Models\MediaInfo;
use App\Http\Controllers\Admin\Media\V1\Request\MediaInfoRequest;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MediaInfoTransformer extends TransformerAbstract
{
    public function transform(MediaInfo $mediaInfo)
    {
        return [
            'id' => $mediaInfo->id,
            'name' => $mediaInfo->name,
            'type' => $mediaInfo->type,
            'date' => $mediaInfo->date ? Carbon::parse($mediaInfo->date)->toDateString() : '',
            'recorder_id' => $mediaInfo->recorder_id,
            'recorder_name' => $mediaInfo->recorder ? $mediaInfo->recorder->name : '',
            'created_at' => $mediaInfo->created_at ? $mediaInfo->created_at->toDateTimeString() : null,
            'updated_at' => $mediaInfo->updated_at ? $mediaInfo->updated_at->toDateTimeString() : null,
            'media_id' => $mediaInfo->media_id,
            'media' => $mediaInfo->media
        ];
    }
}