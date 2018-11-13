<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Request\CouponBatchRequest;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;


class CouponBatchController extends Controller
{

    public function index(CouponBatchRequest $request)
    {
        $now = Carbon::now()->toDateTimeString();
        $batches = CouponBatch::query()
            ->join('coupon_batch_policy', 'coupon_batch_policy.coupon_batch_id', '=', 'coupon_batches.id')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('coupon_batch_policy.policy_id', $request->policy_id)
            ->orderByDesc('sort_order')
            ->selectRaw('coupon_batches.*')
            ->get();

        abort_if($batches->isEmpty(), 500, '无可用优惠券');

        return $this->response->collection($batches, new CouponBatchTransformer());

    }

}
