<?php

namespace App\Observers;

use App\Http\Controllers\Admin\Common\V3\Models\Board;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Resource\V1\Models\ActivityMedia;
use App\Models\Customer;
use Log;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ActivityMediaObserver
{
    public function updated(ActivityMedia $activityMedia)
    {
        if ($activityMedia->status === 1) {
            $board = Board::query()->where('activity_media_id', $activityMedia->id)->firstOrFail();
            $board->update(['image_url' => $activityMedia->url]);
        }
    }
}