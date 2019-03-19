<?php

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use Illuminate\Database\Seeder;

class CouponBatchWriteOffCustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //填充核销人员表
        $couponBatches = CouponBatch::All();

        $couponBatches->each(function ($couponBatch) {
            $couponBatch->company->customers->each(function ($customer) use($couponBatch){
                $couponBatch->writeOffCustomers()->attach($customer);
            });
        });

    }
}
