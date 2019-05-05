<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/26
 * Time: 下午6:32
 */

namespace App\Http\Controllers\Admin\Resource\V1\Transformer;


use App\Http\Controllers\Admin\Resource\V1\Models\ActivityMedia;
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
            'status' => $media->status,
            'audit_user' => $media->audit_user_id ? $media->auditUser->name : null,
            'activity_name' => $media->activity->name,
            'created_at' => $media->created_at->toDateTimeString(),
            'updated_at' => $media->updated_at->toDateTimeString(),
        ];
    }
}