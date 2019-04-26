<?php

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use Illuminate\Database\Seeder;

class UpdateSceneTypeForCouponBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       CouponBatch::query()->whereNull('scene_type')
           ->each(function ($couponBatch) {
              $couponBatch->update(['scene_type' => 4]);
           });
    }
}
