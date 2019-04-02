<?php
/**
 * Created by IntelliJ IDEA.
 * User: chenzhong
 * Date: 2019-03-23
 * Time: 14:34
 */

namespace App\Traits;

use App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch as CouponBatchModel;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use Carbon\Carbon;
use DB;


trait CouponBatch
{
    public function generate($oid, $belong)
    {
        $policyID = $this->getPolicy($oid, $belong);
        $couponBatchPolicies = $this->getCouponBatchPolicies($policyID);


        /**
         * 移除不符合条件的优惠券规则
         */
        foreach ($couponBatchPolicies as $key => $couponBatchPolicy) {

            if (!$this->isLimitStock($couponBatchPolicy)) {
                continue;
            }

            if ($this->checkStock($couponBatchPolicy)) {
                unset($couponBatchPolicies[$key]);
                continue;
            }

            if ($this->checkDynamicStock($couponBatchPolicy)) {
                unset($couponBatchPolicies[$key]);
                continue;
            }

            if ($this->checkDailyStock($couponBatchPolicy)) {
                unset($couponBatchPolicies[$key]);
                continue;
            }
        }

        abort_if($this->checkRate($couponBatchPolicies), 500, '无可用优惠券');

        return $this->getCouponBatch($couponBatchPolicies);
    }

    public function getCouponBatch($couponBatchPolicies)
    {
        /** @var \App\Http\Controllers\Api\V1\Coupon\Models\CouponBatch $targetCouponBatch */
        $targetCouponBatch = getRand($couponBatchPolicies);

        return CouponBatchModel::findOrFail($targetCouponBatch->coupon_batch_id);

    }

    public function isLimitStock($couponBatchPolicy): bool
    {
        return !$couponBatchPolicy->pmg_status && !$couponBatchPolicy->dmg_status;
    }

    public function getCouponBatchPolicies($policyID): array
    {
        $query = DB::table('coupon_batch_policy');

        $couponBatchPolicies = $query->join('coupon_batches', 'coupon_batch_id', '=', 'coupon_batches.id')
            ->where('policy_id', '=', $policyID)
            ->where('coupon_batches.is_active', 1)
            ->get();

        abort_if($couponBatchPolicies->isEmpty(), 500, '无可用优惠券');

        return $couponBatchPolicies->toArray();
    }

    public function getPolicy($oid, $belong)
    {
        $policy = PolicyLaunch::query()->where('oid', $oid)
            ->where('belong', $belong)
            ->firstOrFail();
        return $policy->policy_id;
    }

    public function checkStock($couponBatchPolicy): bool
    {
        return $couponBatchPolicy->stock <= 0;
    }

    public function checkDynamicStock($couponBatchPolicy): bool
    {
        if ($couponBatchPolicy->dynamic_stock_status) {
            $count = Coupon::query()->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->whereIn('status', [0, 3])
                ->where('coupon_batch_id', $couponBatchPolicy->id)->count('id');
            $dynamicStock = $couponBatchPolicy->stock - $count;
            return $dynamicStock <= 0;
        }

        return false;
    }

    public function checkDailyStock($couponBatchPolicy): bool
    {
        $now = Carbon::now()->toDateString();
        $coupon = Coupon::query()->where('coupon_batch_id', $couponBatchPolicy->id)
            ->whereRaw("date_format(created_at,'%Y-%m-%d')='$now'")
            ->selectRaw('count(coupon_batch_id) as day_receive')->first();

        return $coupon->day_receive >= $couponBatchPolicy->day_max_get;
    }

    public function checkRate($couponBatchPolicies): bool
    {
        return collect($couponBatchPolicies)->sum('rate') === 0;
    }

}