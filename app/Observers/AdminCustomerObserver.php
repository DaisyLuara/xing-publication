<?php

namespace App\Observers;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Models\Customer;
use Log;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class AdminCustomerObserver
{
    public function created(Customer $customer)
    {
        //新增联系人核销权限
        CouponBatch::query()->where('company_id', $customer->company_id)
            ->each(static function ($couponBatch) use ($customer) {
                $couponBatch->writeOffCustomers()->attach($customer);
            });
    }
}