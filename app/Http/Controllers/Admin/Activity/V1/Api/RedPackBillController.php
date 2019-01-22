<?php

namespace App\Http\Controllers\Admin\Activity\V1\Api;

use App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill;
use App\Http\Controllers\Admin\Activity\V1\Transformer\RedPackBillTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RedPackBillController extends Controller
{

    public function index(Request $request, RedPackBill $redPackBill)
    {

        $query = $redPackBill->query();

        //优惠券code
        if ($request->has('coupon_code')) {
            $query->where('coupon_code', 'like', '%' . $request->coupon_code . '%');
        }

        //优惠券
        if ($request->has('coupon_batch_id')) {
            $query->where('coupon_batch_id', $request->coupon_batch_id);
        }

        //状态
        if ($request->has('coupon_batch_id')) {
            $query->where('return_code', 'SUCCESS');
        }

        //商户订单号
        if ($request->has('mch_billno')) {
            $query->where('mch_billno', 'like', '%' . $request->mch_billno . '%');
        }

        //用户open_id
        if ($request->has('re_openid')) {
            $query->where('re_openid', 'like', '%' . $request->re_openid . '%');
        }


        $redPackBills = $query->orderByDesc('id')->paginate(10);
        return $this->response->paginator($redPackBills, new RedPackBillTransformer());
    }


}
