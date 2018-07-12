<?php

namespace App\Observers;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class CouponBatchObserver
{
    public function saving(CouponBatch $couponBatch)
    {
        if ($couponBatch->start_date) {
            $couponBatch->start_date = date('Y-m-d H:i:s', $couponBatch->start_date);
        }

        if ($couponBatch->end_date) {
            $couponBatch->end_date = date('Y-m-d H:i:s', $couponBatch->end_date);
        }
    }
}