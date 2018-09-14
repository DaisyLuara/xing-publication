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
use Log;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;


class CouponController extends Controller
{
    public function getCouponBatch(CouponBatch $couponBatch)
    {
        if ($couponBatch->stock <= 0) {
            abort(500, '优惠券已发完');
        }

        $now = Carbon::now()->toDateString();
        if ($couponBatch->dmg_status == 0) {
            $coupon = Coupon::query()->where('coupon_batch_id', $couponBatch->id)
                ->whereRaw("date_format(created_at,'%Y-%m-%d')='$now'")
                ->selectRaw("count(coupon_batch_id) as day_receive")->first();

            if ($coupon->day_receive >= $couponBatch->day_max_get) {
                abort(500, '该券今日已发完，明天再来领取吧！');
            }
        }

        return $this->response->item($couponBatch, new CouponBatchTransformer());

    }

    /**
     * 根据策略获取优惠券规则
     * @param CouponRequest $request
     * @return mixed
     */
    public function getCouponBatches(CouponRequest $request)
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
        if ($couponBatch->stock <= 0) {
            abort(500, '优惠券已发完!');
        }

        if ($couponBatch->third_code) {
            $result = $this->sendMallCooCoupon($mobile, $couponBatch->third_code);
            if ($result['Code'] != 1) {
                abort(500, $result['Message']);
            }

            if (!$result['Data'][0]['IsSuccess']) {
                abort(500, $result['Data'][0]['FailReason']);
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
            $couponBatch->decrement('stock');

        } else {

            $couponBatchId = $couponBatch->id;

            $now = Carbon::now()->toDateString();
            if ($couponBatch->dmg_status == 0) {
                $coupon = Coupon::query()->whereIn('coupon_batch_id', [3, 4, 5, 6])
                    ->whereRaw("date_format(created_at,'%Y-%m-%d')='$now'")
                    ->selectRaw("count(coupon_batch_id) as day_receive")->first();

                if ($coupon->day_receive >= $couponBatch->day_max_get) {
                    abort(500, '该券今日已发完，明天再来领取吧！');
                }
            }

            if ($couponBatch->pmg_status == 0) {
                $coupons = Coupon::query()->where('mobile', $mobile)->where('coupon_batch_id', $couponBatchId)->get();
                if ($coupons->count() >= $couponBatch->people_max_get) {
                    abort(500, '该优惠券每人最多领取' . $couponBatch->people_max_get . '张');
                }
            }

            $coupon = Coupon::create([
                'code' => uniqid(),
                'mobile' => $mobile,
                'coupon_batch_id' => $couponBatch->id,
                'status' => 3,
            ]);

            $couponBatch->decrement('stock');

            $this->sendCouponMsg($mobile, $couponBatch, $easySms);
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

    private function sendCouponMsg($mobile, CouponBatch $couponBatch, EasySms $easySms)
    {
        if (!app()->environment('production')) {
            $allowed = [15921145624, 13818403072, 13052361619, 18616348089, 15856363087, 16602119264];
            if (!in_array($mobile, $allowed)) {
                abort(422, '非法手机号码');
            }
        }

        switch ($couponBatch->name) {
            case '平安符':
                $content = "【星视度】恭喜您获得“平安符”一枚，凭此短信到服务台免费领取，快快领取使用吧。";
                break;
            case '西树泡芙5元代金券':
                $content = '【星视度】恭喜您获得“西树泡芙5元代金券”，凭此短信到服务台免费领取。使用期限10月31日，快快领取使用吧。';
                break;
            case '熊本熊水杯':
                $content = '【星视度】恭喜您获得“熊本熊水杯”一个，凭此短信到服务台免费领取，快快领取使用吧。';
                break;
            case '苏小柳100元代金券':
                $content = '【星视度】恭喜您获得“苏小柳100元代金券”，凭此短信到服务台免费领取。使用期限10月31日，快快领取使用吧。';
                break;
            default:
                return;
        }

        try {
            $result = $easySms->send($mobile, [
                'content' => $content,
            ]);
            Log::info('send_coupon_msg', $result);

        } catch (\Exception $exception) {
            Log::info('send_msg_exceptions', ['msg' => $exception->getMessage()]);
        }

    }

}
