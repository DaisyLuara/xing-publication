<?php

namespace App\Http\Controllers\Admin\Activity\V1\Api;

use App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill;
use App\Http\Controllers\Admin\Activity\V1\Transformer\RedPackBillTransformer;
use App\Http\Controllers\Controller;
use App\Jobs\ResendRedpackJob;
use Illuminate\Http\Request;

class RedPackBillController extends Controller
{

    public function index(Request $request, RedPackBill $redPackBill)
    {

        $query = $redPackBill->query();

        //优惠券code
        if ($request->has('coupon_code')) {
            $query->where('coupon_code', $request->coupon_code);
        }

        //优惠券
        if ($request->has('coupon_batch_id')) {
            $query->where('coupon_batch_id', $request->coupon_batch_id);
        }

        //状态
        if ($request->has('result_code')) {
            $query->where('result_code', $request->result_code);
        }

        //商户订单号
        if ($request->has('mch_billno')) {
            $query->where('mch_billno', $request->mch_billno);
        }

        //用户open_id
        if ($request->has('re_openid')) {
            $query->where('re_openid', $request->re_openid);
        }

        //发放红包使用场景
        if ($request->has('scene_id')) {
            $query->where('scene_id', $request->scene_id);
        }

        $redPackBills = $query->orderByDesc('id')->paginate(10);
        return $this->response->paginator($redPackBills, new RedPackBillTransformer());
    }

    public function resend(RedPackBill $redPackBill)
    {
        if ($redPackBill->result_code != 'FAIL') {
            abort(500, '业务结果正常 无需重发');
        }
        ResendRedpackJob::dispatch($redPackBill)->onQueue('resend_redpack');
    }

}
