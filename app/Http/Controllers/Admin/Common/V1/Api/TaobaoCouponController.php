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
use App\Http\Controllers\Admin\Common\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Admin\Common\V1\Request\TaobaoCouponRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Log;
use DB;


class TaobaoCouponController extends Controller
{
    /**
     * 获取用户优惠券
     * @param TaobaoCouponRequest $request
     * @return mixed
     */
    public function show(CouponBatch $couponBatch, TaobaoCouponRequest $request)
    {
        $coupon = Coupon::query()->where('coupon_batch_id', $couponBatch->id)->where('taobao_user_id', $request->openuid)->first();
        abort_if(!$coupon, 204);

        return $this->response->item($coupon, new CouponTransformer());
    }


    /**
     * 发送优惠券
     * @param CouponBatch $couponBatch
     * @param TaobaoCouponRequest $request
     * @return mixed
     */
    public function store(CouponBatch $couponBatch, TaobaoCouponRequest $request)
    {
        Log::info('taobao_coupon_store', $request->all());
        $taobaoUserID = $request->openuid;

        //优惠券时间判断
        $now = Carbon::now()->timestamp;
        $startDate = strtotime($couponBatch->start_date);
        $endDdate = strtotime($couponBatch->end_date);

        abort_if($now <= $startDate, 500, '活动未开启!');
        abort_if($now >= $endDdate, 500, '活动已结束!');

        //同一种优惠券只能领取一次
        $coupon = Coupon::query()->where('coupon_batch_id', $couponBatch->id)->where('taobao_user_id', $taobaoUserID)->first();
        if ($coupon) {
            return $this->response->item($coupon, new CouponTransformer());
        }

        if (!$couponBatch->dmg_status && !$couponBatch->pmg_status && $couponBatch->stock <= 0) {
            abort(500, '优惠券已发完!');
        }

        //每天最大领取量
        if (!$couponBatch->dmg_status) {
            $dateString = Carbon::now()->toDateString();
            $coupon = Coupon::query()->where('coupon_batch_id', $couponBatch->id)
                ->whereRaw("date_format(created_at,'%Y-%m-%d')='$dateString'")
                ->selectRaw("count(coupon_batch_id) as day_receive")->first();

            if ($coupon->day_receive >= $couponBatch->day_max_get) {
                abort(500, '该券今日已发完，明天再来领取吧！');
            }
        }

        //用户最大领取量
        if (!$couponBatch->pmg_status) {
            $coupons = Coupon::query()->where('taobao_user_id', $taobaoUserID)
                ->where('coupon_batch_id', $couponBatch->id)
                ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->get();

            if ($coupons->count() >= $couponBatch->people_max_get) {
                abort(500, '您今天已经领过了，请明天再来!');
            }
        }

        //创建优惠券
        $coupon = Coupon::create([
            'code' => uniqid(),
            'coupon_batch_id' => $couponBatch->id,
            'status' => 3,
            'taobao_user_id' => $taobaoUserID,
        ]);

        //减少库存
        if (!$couponBatch->pmg_status && !$couponBatch->pmg_status) {
            $couponBatch->decrement('stock');
        }

        return $this->response->item($coupon, new CouponTransformer());

    }

    /**
     * 核销
     * @param TaobaoCouponRequest $request
     * @return mixed
     */
    public function update(TaobaoCouponRequest $request)
    {
        Log::info('taobao_coupon_update', $request->all());
        $taobaoUserID = $request->openuid;
        $coupon = Coupon::query()->where('code', $request->code)
            ->where('taobao_user_id', $taobaoUserID)
            ->firstOrFail();
        if ($coupon->status != 1) {
            $coupon->update(['status' => 1]);
            $op_time = date('Y-m-d H:i:s');
            $insertData = array_merge(
                [
                    'op_time' => $op_time,
                    'coupon_id' => $coupon->coupon_batch_id,
                    'code' => $coupon->code,
                    'action' => 'RECEIVE_COUPONS',
                    'created_at' => $op_time,
                    'updated_at' => $op_time,
                ],
                $request->only(['game_name', 'device_code', 'user_nick']));
            DB::connection('xs')->table('game_feedback')->insert($insertData);
        }
        return $this->response->item($coupon, new CouponTransformer());
    }

}
