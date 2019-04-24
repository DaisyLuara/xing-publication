<?php

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use Illuminate\Database\Seeder;

class CouponBatchPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //金展抽奖策略
        $policy = Policy::query()->findOrFail(71);
        $couponBatches = CouponBatch::query()->where('company_id', 153)->get();

        $couponBatches->each(function ($item) use ($policy) {
            $policy->batches()->attach($item->id, ['type' => 'rate', 'rate' => 10]);
        });
    }
}
