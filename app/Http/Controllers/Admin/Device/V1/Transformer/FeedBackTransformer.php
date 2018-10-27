<?php

namespace App\Http\Controllers\Admin\Device\V1\Transformer;

use App\Http\Controllers\Admin\Device\V1\Models\FeedBack;
use League\Fractal\TransformerAbstract;

class FeedBackTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['point', 'project'];

    public function transform(FeedBack $feedback)
    {
        return [
            'id' => $feedback->id,
            'device_code' => $feedback->device_code,
            'action' => $feedback->action,
            'coupon_id' => $feedback->coupon_id,
            'user_nick' => $feedback->user_nick,
            'game_name' => $feedback->game_name,
            'created_at' => $feedback->created_at->toDateTimeString(),
        ];
    }

}