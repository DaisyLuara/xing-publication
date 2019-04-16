<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Notifications\BaseNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CouponBatchEndDateNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow('PRC')->toDateString();
        $afterTomorrow = Carbon::tomorrow('PRC')->addDay(1)->toDateString();


        $couponBatches = CouponBatch::query()
            ->where('is_active', '=', 1)
            ->where('is_fixed_date', '=', 1)
            ->whereRaw("end_date >= '$tomorrow' and end_date < '$afterTomorrow'")
            ->get();


        /** @var CouponBatch $couponBatch */
        foreach ($couponBatches as $couponBatch) {

            if (!$couponBatch->user) {
                continue;
            }

            $notification_params = [
                'id' => $couponBatch->id,
                'user_id' => $couponBatch->user->id,
                'user_name' => $couponBatch->user->name,
                'type' => 'coupon_batch',
                'reply_content' => "您创建的优惠券即将过期，请及时查看"
                    . "   \n优惠券名称：" . $couponBatch->name
                    . "   \n公司名称：" . ($couponBatch->company ? $couponBatch->company->name : '')
                    . "   \n创建人：" . $couponBatch->user->name
                    . "   \n过期时间：" . (Carbon::parse($couponBatch->end_date)->toDateString())
                    . "   "];

            $couponBatch->user->notify(new BaseNotification($notification_params));
        }

    }
}
