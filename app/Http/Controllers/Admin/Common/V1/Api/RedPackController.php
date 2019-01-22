<?php
/**
 * Created by IntelliJ IDEA.
 * User: chenzhong
 * Date: 2019/1/22
 * Time: 下午4:25
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;


use App\Http\Controllers\Admin\Common\V1\Request\RedPackRequest;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Controller;
use EasyWeChat;
use App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill;

class RedPackController extends Controller
{
    public function store($code, RedPackRequest $request)
    {

        $userID = $request->has('sign') ? decrypt($request->get('sign')) : 0;
        $coupon = Coupon::query()->where('code', $code)->where('user_id', $userID)->firstOrFail();
        $couponBatch = $coupon->coupon_batch;

        $payment = EasyWeChat::payment();
        $redpack = $payment->redpack;

        $redpackData = [
            'mch_billno' => date('YmdHis') . uniqid(),
            'send_name' => '测试',
            're_openid' => '',
            'total_num' => 1,
            'total_amount' => 100 * $couponBatch->amount,
            'wishing' => '新年快乐!',
            'act_name' => '刮卡抽奖！',
            'remark' => '刮卡抽奖',
            'scene_id' => 'PRODUCT_2',
        ];

        $result = $redpack->sendNormal($redpackData);
        if ($result['result_code'] == 'FAIL') {
//            ding()->with()->text();
            abort(500, $result['return_msg']);
        }
        //标记优惠券为已使用-不再发放现金红包
        $coupon->update(['status' => 1]);


        return $result;
    }

}