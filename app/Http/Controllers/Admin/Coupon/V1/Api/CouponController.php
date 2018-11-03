<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Api;


use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Coupon\V1\Request\CouponRequest;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{

    public function index(Coupon $coupon, CouponRequest $request)
    {
        $query = $coupon->query();

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('coupon_batch_id')) {
            $query->where('coupon_batch_id', $request->get('coupon_batch_id'));
        }

        if ($request->has('company_id')) {
            $companyID = $request->company_id;
            $query->whereHas('couponBatch', function ($query) use ($companyID) {
                $query->where('company_id', $companyID);
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')]);
        }

        $coupon = $query->orderByDesc('id')->paginate(10);

        return $this->response->paginator($coupon, new CouponTransformer());
    }


}