<?php

use App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch;
use Illuminate\Database\Seeder;

class UserCouponBatchForWuYueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = 'SELECT t.rank, t.id, t.score, r.user_id
                    FROM 
                      (SELECT u.id, u.score, @rank := @rank + 1, 
                        @last_rank := CASE 
                          WHEN @last_score = u.score
                            THEN @last_rank 
                          WHEN @last_score := u.score 
                            THEN @rank 
                        END AS rank    
                      FROM 
                        (SELECT * FROM jingsaas.oc_game_attribute where belong = "h5_beat_pig" ORDER BY score DESC) u, 
                        (SELECT @rank := 0, @last_score := NULL, @last_rank := 0) r
                    ) t LEFT JOIN jingsaas.oc_game_result r on t.id = r.game_attribute_id;';
        $result = DB::select($sql);

        foreach ($result as $item) {
            $rank = $item->rank;
            switch ($rank) {
                case $rank <= 10:
                    $couponBatchId = 1078;
                    break;
                case $rank > 10 && $rank <= 20:
                    $couponBatchId = 1093;
                    break;
                case $rank > 20  && $rank <= 120:
                    $couponBatchId = 1094;
                    break;
                case $rank > 120  && $rank <= 400:
                    $couponBatchId = 1095;
                    break;
                case $rank > 400  && $rank <= 600:
                    $couponBatchId = 1095;
                    break;
                case $rank > 600  && $rank <= 1000:
                    $couponBatchId = 1095;
                    break;
                default:
                    return;
            }

            UserCouponBatch::query()->create([
                'wx_user_id' => $item->user_id,
                'coupon_batch_id' => $couponBatchId,
                'belong' => 'h5_beat_pig',
            ]);
        }
    }
}
