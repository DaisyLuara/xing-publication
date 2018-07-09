<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponCountLog;
use App\Http\Controllers\Admin\Coupon\V1\Request\CouponRequest;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use Overtrue\EasySms\EasySms;

class CouponController extends Controller
{
    public function getCouponStatus(Request $request)
    {
        $companyId = $request->company_id;

        $couponBatches = CouponBatch::query()
            ->where('company_id', $companyId)
            ->where('is_active', '=', 1)
            ->where('stock', '<>', 0)
            ->get();

        if ($couponBatches->isEmpty()) {
            return $this->response->error('优惠券领完了',200);
        }

        $status = 0;
        foreach ($couponBatches as $couponBatch) {
            $date = Carbon::now()->toDateString();
            $couponCountLog = CouponCountLog::query()
                ->where('coupon_batch_id', $couponBatch->id)
                ->whereRaw("date_format(date,'%Y-%m-%d') ='$date'")
                ->first();

            if ($couponBatch->dmg_status == 0 && $couponCountLog->receive_num >= $couponBatch->day_max_get) {
                $status += 1;
            }
        }

        if ($status == $couponBatches->count()) {
            return $this->response->error('优惠券领完了',200);
        }

        return $this->response->collection($couponBatches);

    }

    public function createCoupon(Request $request)
    {
        $companyId = $request->company_id;
        $couponBatches = CouponBatch::query()
            ->where('company_id', $companyId)
            ->where('is_active', '=', 1)
            ->where('stock', '<>', 0)
            ->get();

        $proArr = [];

        foreach ($couponBatches as $couponBatch) {
            $proArr[] = ['id' => $couponBatch->id, 'v' => $couponBatch->couponPolicy->rate];
        }

        $result = getRand($proArr);
        $couponBatch = CouponBatch::findOrFail($result['id']);

        $coupon = Coupon::create(['coupon_batch_id' => $couponBatch->id, 'status' => 0]);

        $date = Carbon::now()->toDateString();

        CouponCountLog::query()
            ->where('coupon_batch_id', $couponBatch->id)
            ->whereRaw(" date_format(date,'%Y-%m-%d') = '$date' ")
            ->increment('create_num');

        CouponCountLog::query()
            ->where('coupon_batch_id', $couponBatch->id)
            ->whereRaw(" date_format(date,'%Y-%m-%d') = '$date' ")
            ->increment('unreceived_num');

        return $this->response->item($coupon, new CouponTransformer());

    }

    public function getCoupon(CouponRequest $request, EasySms $easySms)
    {
        $mobile = $request->mobile;
        $couponId = $request->coupon_id;
        $couponBatchId = $request->coupon_batch_id;

        $coupon = Coupon::findOrFail($couponId);
        $couponBatch = CouponBatch::findOrFail($couponBatchId);

        $coupons = Coupon::query()->where('mobile', $mobile)->where('coupon_batch_id', $couponBatchId)->get();
        $couponCountLog = CouponCountLog::query()->where('coupon_batch_id', $couponBatchId)->first();

        if ($couponBatch->dmg_status == 0 && $couponCountLog->receive_num >= $couponBatch->day_max_get) {
            return Response::json('该券今日已发完，明天再来领取吧！', 200);
        }

        if ($couponBatch->pmg_status == 0 && $coupons->count() >= $couponBatch->people_max_get) {
            return Response::json('该优惠券每人最多领取' . $couponBatch->people_max_get . '张', 200);
        }

        Coupon::query()->where('id', $couponId)->update(['code' => str_random(6) . date('YmdHis'), 'mobile' => $mobile, 'status' => 3]);

        CouponBatch::query()->where('id', $couponBatch->id)->decrement('stock');
        $date = Carbon::now()->toDateString();

        CouponCountLog::query()->where('coupon_batch_id', $couponBatchId)
            ->whereRaw(" date_format(date,'%Y-%m-%d') = '$date' ")
            ->increment('receive_num');

        CouponCountLog::query()->where('coupon_batch_id', $couponBatchId)
            ->whereRaw(" date_format(date,'%Y-%m-%d') = '$date' ")
            ->decrement('unreceived_num');

        return $this->response->item($coupon, new CouponTransformer());
    }

}
