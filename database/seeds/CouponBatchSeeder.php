<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Models\User;

class CouponBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //根据BD生成公司
        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', '=', 'user');
        })->first();

        $companyID = Company::query()->insertGetId([
            'user_id' => $user->id,
            'name' => '星视度',
            'address' => '上海市浦东新区浦东南路1118号',
            'status' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        //生成优惠券规则

        CouponBatch::query()->insert([
            [
                'company_id' => $companyID,
                'create_user_id' => $user->id,
                'bd_user_id' => $user->id,
                'amount' => 0,
                'count' => 40,
                'stock' => 40,
                'people_max_get' => 1,
                'pmg_status' => 0,
                'day_max_get' => 1,
                'dmg_status' => 0,
                'is_fixed_date' => 0,
                'delay_effective_day' => 0,
                'effective_day' => 1,
                'start_date' => '2018-06-20 00:00:00',
                'end_date' => '2018-07-02 23:59:59',
                'name' => '恭喜中奖',
                'description' => '恭喜中奖',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'company_id' => $companyID,
                'create_user_id' => $user->id,
                'bd_user_id' => $user->id,
                'amount' => 0,
                'count' => 100000,
                'stock' => 100000,
                'people_max_get' => 0,
                'pmg_status' => 1,
                'day_max_get' => 0,
                'dmg_status' => 1,
                'is_fixed_date' => 0,
                'delay_effective_day' => 0,
                'effective_day' => 1,
                'start_date' => '2018-06-20 00:00:00',
                'end_date' => '2018-07-02 23:59:59',
                'name' => '谢谢参与',
                'description' => '谢谢参与',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'company_id' => $companyID,
                'create_user_id' => $user->id,
                'bd_user_id' => $user->id,
                'amount' => 0,
                'count' => 200,
                'stock' => 200,
                'people_max_get' => 1,
                'pmg_status' => 0,
                'day_max_get' => 1,
                'dmg_status' => 0,
                'is_fixed_date' => 1,
                'delay_effective_day' => 0,
                'effective_day' => 0,
                'start_date' => '2018-06-20 00:00:00',
                'end_date' => '2018-07-02 23:59:59',
                'name' => '龙虾刑警',
                'description' => '龙虾刑警观影券',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'company_id' => $companyID,
                'create_user_id' => $user->id,
                'bd_user_id' => $user->id,
                'amount' => 0,
                'count' => 100000,
                'stock' => 100000,
                'people_max_get' => 0,
                'pmg_status' => 1,
                'day_max_get' => 0,
                'dmg_status' => 1,
                'is_fixed_date' => 0,
                'delay_effective_day' => 0,
                'effective_day' => 1,
                'start_date' => '2018-06-20 00:00:00',
                'end_date' => '2018-07-02 23:59:59',
                'name' => '洗车券',
                'description' => '洗车券',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        //生成优惠券策略
        $policy = Policy::query()->create(['create_user_id' => $user->id, 'bd_user_id' => $user->id, 'company_id' => $companyID, 'name' => '龙虾刑警']);

        $couponBatches = CouponBatch::query()->get();
        foreach ($couponBatches as $couponBatch) {
            $couponBatch->policy()->attach($policy->id, [
                    'min_age' => 0 + rand(1, 10),
                    'max_age' => 10 + rand(1, 10),
                    'type' => 'age'
                ]
            );
        }
    }
}
