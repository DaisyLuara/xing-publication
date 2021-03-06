<?php

namespace App\Http\Controllers\Admin\Common\V3\Api;

use App\Http\Controllers\Admin\Common\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Admin\Common\V2\Request\UserCouponRequest;
use App\Http\Controllers\Admin\Common\V2\Request\CouponRequest;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberSession;
use App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Traits\CouponBatch as CouponBatchTrait;
use App\Http\Controllers\Controller;
use App\Models\WeChatUser;
use Carbon\Carbon;
use Log;
use DB;

class CouponController extends Controller
{
    use CouponBatchTrait;

    /**
     * 发送优惠券
     * @param CouponRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CouponRequest $request)
    {
        /** @var ArMemberSession|WeChatUser $user */
        $user = $this->getUser($request);
        $userQuerySql = $this->getUserQuerySql($request, $user);

        /** @var PolicyLaunch $policyLaunch */
        $policyLaunch = PolicyLaunch::query()->where('belong', $request->get('belong'))
            ->where('oid', $request->get('oid'))->firstOrFail();
        $policy = $policyLaunch->policy;

        //策略每人抽奖次数校验
        if (!$policy->per_person_unlimit) {
            $couponPerPersonGet = Coupon::query()->whereRaw($userQuerySql)
                ->where('belong', $request->get('belong'))->count();
            abort_if($couponPerPersonGet >= $policy->per_person_times, 500, '优惠券领取数量已达上限!');
        }

        //策略每人每天抽奖次数校验
        if (!$policy->per_person_per_day_unlimit) {
            $couponPerPersonPerDayGet = Coupon::query()->whereRaw($userQuerySql)
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->where('belong', $request->get('belong'))->count();
            abort_if($couponPerPersonPerDayGet >= $policy->per_person_per_day_times, 500, '今日领券数量已达上限,请明天再来!');
        }

        /** @var CouponBatch $couponBatch */
        $couponBatch = $user->userCouponBatches()->firstOrFail();
        $couponBatchId = $couponBatch->id;

        //库存校验
        if (!$couponBatch->dmg_status && !$couponBatch->pmg_status && $couponBatch->stock <= 0) {
            abort(500, '优惠券已发完!');
        }

        //当天库存校验
        if (!$couponBatch->dmg_status) {
            /** @var Coupon $coupon */
            $coupon = Coupon::query()->where('coupon_batch_id', $couponBatchId)
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->selectRaw('count(coupon_batch_id) as day_receive')
                ->first();
            abort_if($coupon->day_receive >= $couponBatch->day_max_get, 500, '该券今日已发完，明天再来领取吧！');
        }

        //每人每天库存校验
        if (!$couponBatch->pmg_status) {
            $coupons = Coupon::query()->whereDate('created_at', Carbon::now()->toDateString())
                ->whereRaw($userQuerySql)->where('coupon_batch_id', $couponBatchId)
                ->get();
            abort_if($coupons->count() >= $couponBatch->people_max_get, 500, '优惠券每人最多领取' . $couponBatch->people_max_get . '张');
        }

        //生成券信息
        $couponData = $this->generateCouponData($couponBatch, $request, $user);

        DB::beginTransaction();
        try {
            $coupon = Coupon::query()->create($couponData);
            $coupon = $this->setCodeImageUrl($coupon, $couponBatch, $request->get('code_type'));

            //不使用系统核销 领取优惠券后 ，自动减去库存
            if (!$couponBatch->write_off_status && !$couponBatch->pmg_status && !$couponBatch->pmg_status) {
                $couponBatch->decrement('stock');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();//事务回滚
            abort(500, $e->getMessage());
        }

        return $this->response->item($coupon, new CouponTransformer());
    }

    /**
     * 获取用户优惠券
     * @param UserCouponRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function getUserCoupon(UserCouponRequest $request)
    {
        $user = $this->getUser($request);
        $userQuerySql = $this->getUserQuerySql($request, $user);

        $query = Coupon::query();

        if ($request->has('qiniu_id')) {
            $query->where('qiniu_id', $request->get('qiniu_id'));
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')]);
        }

        if ($request->has('coupon_batch_id')) {
            $query->where('coupon_batch_id', $request->get('coupon_batch_id'));
        }

        if ($request->has('belong')) {
            $query->where('belong', $request->get('belong'));
        }

        if ($request->has('ser_timestamp') && $request->get('ser_timestamp') > 0) {
            $query->where('ser_timestamp', $request->get('ser_timestamp'));
        }

        $coupon = $query->whereRaw($userQuerySql)->first();

        abort_if(!$coupon, 204);

        //优惠券二维码
        $coupon = $this->setCodeImageUrl($coupon, $coupon->couponBatch, $request->get('code_type'));

        return $this->response->item($coupon, new CouponTransformer());
    }

}
