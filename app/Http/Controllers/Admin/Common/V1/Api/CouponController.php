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
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Common\V1\Request\CouponRequest;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
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
            return $this->response->error('优惠券领完了', 200);
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
            return $this->response->error('优惠券领完了', 200);
        }

        return $this->response->collection($couponBatches);

    }

    /**
     * 根据策略获取优惠券规则
     * @param CouponRequest $request
     * @return mixed
     */
    public function getCouponBatch(CouponRequest $request)
    {
        $policy = Policy::query()->findOrFail($request->policy_id);

        $query = DB::table('coupon_batch_policy');
        if ($request->has('age')) {
            $query->where('max_age', '>=', $request->age)->where('min_age', '<=', $request->age);
        }

        if ($request->has('gender')) {
            $query->where('gender', '=', $request->gender);
        }

        $couponBatchPolicies = $query->where('policy_id', '=', $policy->id)->get();

        if ($couponBatchPolicies->isEmpty()) {
            return $this->response->error('无可用优惠券', 500);
        }

        $targetCouponBatch = getRand($couponBatchPolicies->toArray());
        $couponBatch = CouponBatch::findOrFail($targetCouponBatch->coupon_batch_id);

        return $this->response->item($couponBatch, new CouponBatchTransformer());

    }

    /**
     * 发送优惠券
     * @param CouponBatch $couponBatch
     * @param CouponRequest $request
     * @param EasySms $easySms
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateCoupon(CouponBatch $couponBatch, CouponRequest $request, EasySms $easySms)
    {
        $mobile = $request->mobile;

        if ($couponBatch->third_code) {
            $result = $this->sendMallCooCoupon($mobile, $couponBatch->third_code);
            if ($result['Code'] != 1) {
                return $this->response->error($result['Message'], 500);
            }

            if (!$result['Data'][0]['IsSuccess']) {
                return $this->response->error($result['Data'][0]['FailReason'], 500);
            }

            $data = $result['Data'];
            $coupon = Coupon::create([
                'code' => $data[0]['VCode'],
                'mobile' => $mobile,
                'coupon_batch_id' => $couponBatch->id,
                'picm_id' => $data[0]['PICMID'],
                'trace_id' => $data[0]['TraceID'],
                'status' => 3,
            ]);

            $stock = $couponBatch->stock - 1;
            $couponBatch->update(['stock' => $stock]);
        } else {

            $couponBatchId = $couponBatch->id;

            $now = Carbon::now()->toDateString();
            if ($couponBatch->dmg_status == 0) {
                $coupon = Coupon::query()->where('coupon_batch_id', $couponBatchId)
                    ->whereRaw("date_format(created_at,'%Y-%m-%d')='$now'")
                    ->selectRaw("count(coupon_batch_id) as day_receive")->first();

                if ($coupon->day_receive >= $couponBatch->day_max_get) {
                    return $this->response->error('该券今日已发完，明天再来领取吧！', 200);
                }
            }

            if ($couponBatch->pmg_status == 0) {
                $coupons = Coupon::query()->where('mobile', $mobile)->where('coupon_batch_id', $couponBatchId)->get();
                if ($coupons->count() >= $couponBatch->people_max_get) {
                    return $this->response->error('该优惠券每人最多领取' . $couponBatch->people_max_get . '张', 200);
                }
            }

            $coupon = Coupon::create([
                'code' => uniqid(),
                'mobile' => $mobile,
                'coupon_batch_id' => $couponBatch->id,
                'status' => 3,
            ]);

            CouponBatch::query()->where('id', $couponBatch->id)->decrement('stock');

        }

        return $this->response->item($coupon, new CouponTransformer());
    }

    private function sendMallCooCoupon($mobile, $picmID)
    {
        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/Coupon/v1/Send/ByMobile/';

        $data = [
            'UserList' => [
                [
                    'BussinessID' => null,
                    'TraceID' => uniqid() . config('mall_coo.app_id'),
                    'PICMID' => $picmID,
                    'Mobile' => $mobile,
                ]
            ]
        ];

        return $mall_coo->send($sUrl, $data);

    }

}
