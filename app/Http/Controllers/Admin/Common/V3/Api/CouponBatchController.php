<?php

namespace App\Http\Controllers\Admin\Common\V3\Api;

use App\Http\Controllers\Admin\Common\V3\Request\CouponBatchRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Traits\CouponBatch;


class CouponBatchController extends Controller
{

    use CouponBatch;

    public function show(CouponBatchRequest $request)
    {
        $couponBatch = $this->generate($request->get('oid'), $request->get('belong'));

        return $this->response->item($couponBatch, new CouponBatchTransformer());
    }
}
