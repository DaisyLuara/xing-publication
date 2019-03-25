<?php

namespace App\Http\Controllers\Admin\Common\V3\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Traits\CouponBatch;
use Illuminate\Http\Request;


class CouponBatchController extends Controller
{

    use CouponBatch;

    public function show(Request $request)
    {
        $couponBatch = $this->generate($request->get('oid'), $request->get('belong'));

        return $this->response->item($couponBatch, new CouponBatchTransformer());
    }
}
