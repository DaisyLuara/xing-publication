<?php

namespace App\Http\Controllers\Admin\Common\V1\Transformer;

use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $notification)
    {
        $data = $notification->data;
        if($data['reply_content']){
            $data['reply_content'] = nl2br($data['reply_content']);
        }
        return [
            'id' => $notification->id,
            'type' => $notification->type,
            'data' => $data,
            'read_at' => $notification->read_at ? $notification->read_at->toDateTimeString() : null,
            'created_at' => $notification->created_at->toDateTimeString(),
            'updated_at' => $notification->updated_at->toDateTimeString(),
        ];
    }
}