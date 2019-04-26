<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/26
 * Time: 下午6:32
 */

namespace App\Http\Controllers\Admin\Media\V1\Transformer;


use App\Http\Controllers\Admin\Media\V1\Models\ActivityMedia;
use League\Fractal\TransformerAbstract;

class ActivityMediaTransformer extends TransformerAbstract
{
    public function transform(ActivityMedia $media): array
    {
        return [
            'id' => $media->id,
            'name' => $media->name,
            'size' => $media->size,
            'url' => $media->url,
            'status' => $media->status
        ];
    }
}