<?php

use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponForJsmDiamondSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 149; $i++) {
            Coupon::query()->create([
                'code' => uniqid(),
                'coupon_batch_id' => 883,
                'status' => 3,
                'member_uid' => 12345,
                'qiniu_id' => 11132139,
                'oid' => 480,
                'utm_source' => 1,
                'belong' => 'Love520Action',
                'start_date' => '2019-04-30 00:00:00',
                'end_date' => '2019-05-31 23:59:59',
            ]);
        }
    }
}
