<?php

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use Illuminate\Database\Seeder;

class CouponBatchForGuoFangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CouponBatch::query()->where('company_id', 166)
            ->each(function ($couponBatch) {
                $couponBatch->update([
                    'is_fixed_date' => 1,
                    'start_date' => '2019-04-12 00:00:00',
                    'end_date' => '2019-04-14 23:59:59',
                    'write_off_status' =>  0,
                    'is_active' => 0
                ]);
            });
    }
}
