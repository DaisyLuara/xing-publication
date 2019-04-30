<?php

namespace App\Http\Controllers\Admin\Common\V3\Api;

use App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Common\V3\Request\CouponBatchRequest;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberSession;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Traits\CouponBatch as CouponBatchTrait;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class CouponBatchController extends Controller
{

    use CouponBatchTrait;

    public function show(CouponBatchRequest $request)
    {
        $couponBatch = $this->generate($request->get('oid'), $request->get('belong'));

        return $this->response->item($couponBatch, new CouponBatchTransformer());
    }

    /**
     * H5策略抽奖
     * @param CouponBatchRequest $request
     * @return mixed
     */
    public function store(CouponBatchRequest $request)
    {
        /** @var ArMemberSession $member */
        $member = ArMemberSession::query()->where('z', $request->get('z'))->firstOrFail();

        /** @var PolicyLaunch $policyLaunch */
        $policyLaunch = PolicyLaunch::query()->where('belong', $request->get('belong'))
            ->where('oid', $request->get('oid'))->firstOrFail();

        $query = DB::table('coupon_batch_policy');
        if ($request->has('age')) {
            $query->where('max_age', '>=', $request->age)->where('min_age', '<=', $request->age);
        }

        if ($request->has('score')) {
            $query->where('max_score', '>=', $request->score)->where('min_score', '<=', $request->score);
        }

        if ($request->has('gender')) {
            $query->where('gender', '=', $request->gender);
        }

        $couponBatchPolicies = $query->join('coupon_batches', 'coupon_batch_id', '=', 'coupon_batches.id')
            ->where('policy_id', '=', $policyLaunch->policy_id)
            ->where('coupon_batches.is_active', 1)
            ->get();

        if ($couponBatchPolicies->isEmpty()) {
            abort(500, '无可用优惠券');
        }

        $couponBatchIDs = [];
        $couponBatchPolicies = $couponBatchPolicies->each(static function ($item) use (&$couponBatchIDs) {
            $couponBatchIDs[] = $item->coupon_batch_id;
        });

        //库存校验
        $couponsDayGetQuery = Coupon::query()->whereIn('coupon_batch_id', $couponBatchIDs)
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->selectRaw('coupon_batch_id, count(*) as day_receive')
            ->groupBy('coupon_batch_id');

        $couponsPersonGetQuery = clone $couponsDayGetQuery;
        $couponsPersonGetQuery->where('member_uid', $member->uid);

        //当天领取数量
        $couponsDayGetArray = array_column($couponsDayGetQuery->get()->toArray(), 'day_receive', 'coupon_batch_id');
        //每人每天领取数量
        $couponsPersonGetArray = array_column($couponsPersonGetQuery->get()->toArray(), 'day_receive', 'coupon_batch_id');

        foreach ($couponBatchPolicies as $key => $couponBatchPolicy) {
            $couponBatchID = $couponBatchPolicy->coupon_batch_id;
            //不符合条件的优惠券
            if (!$couponBatchPolicy->pmg_status && !$couponBatchPolicy->dmg_status && $couponBatchPolicy->stock <= 0) {
                unset($couponBatchPolicies[$key]);
                continue;
            }

            //当天库存校验
            if (!$couponBatchPolicy->dmg_status && array_key_exists($couponBatchID, $couponsDayGetArray)
                && $couponBatchPolicy->day_max_get <= $couponsDayGetArray[$couponBatchID]) {
                unset($couponBatchPolicies[$key]);
                continue;
            }

            //每人每天库存校验
            if (!$couponBatchPolicy->pmg_status && array_key_exists($couponBatchID, $couponsPersonGetArray)
                && $couponBatchPolicy->people_max_get <= $couponsPersonGetArray[$couponBatchID]) {
                unset($couponBatchPolicies[$key]);
                continue;
            }
        }

        if (collect($couponBatchPolicies)->sum('rate') === 0) {
            abort(500, '无可用优惠券');
        }

        $targetCouponBatch = getRand($couponBatchPolicies);

        $couponBatch = CouponBatch::query()->findOrFail($targetCouponBatch->coupon_batch_id);

        UserCouponBatch::query()->create([
            'member_uid' => $member->uid,
            'coupon_batch_id' => $couponBatch->id,
            'belong' => $request->get('belong'),
        ]);

        return $this->response->item($couponBatch, new CouponBatchTransformer());

    }

}
