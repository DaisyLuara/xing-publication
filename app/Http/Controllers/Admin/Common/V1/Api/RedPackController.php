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
use App\Models\WeChatUser;
use EasyWeChat;
use App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill;

class RedPackController extends Controller
{
    public function store($code, RedPackRequest $request)
    {
        $userID = $request->has('sign') ? decrypt($request->get('sign')) : 0;
        $coupon = Coupon::query()->with('couponBatch')->where('code', $code)->where('wx_user_id', $userID)->firstOrFail();
        $couponBatch = $coupon->couponBatch;
        $wxUser = WeChatUser::findOrFail($userID);

        //已经发送过红包
        abort_if($coupon->status == 1, 500, '已经发送过红包了');

        $payment = EasyWeChat::payment();
        $redpack = $payment->redpack;

        $mchBillno = date('YmdHis') . uniqid();
        $redpackData = [
            'mch_billno' => $mchBillno,
            'send_name' => $couponBatch->amount . '元',
            're_openid' => $wxUser->openid,
            'total_num' => 1,
            'total_amount' => 100 * $couponBatch->amount,
            'wishing' => '新年快乐!',
            'act_name' => '刮卡抽奖！',
            'remark' => '刮卡抽奖',
            'scene_id' => 'PRODUCT_2',
        ];

        //发送红包
        $result = $redpack->sendNormal($redpackData);

        //添加流水记录
        $redpackBillData = [
            'coupon_batch_id' => $coupon->coupon_batch_id,
            'coupon_code' => $coupon->code,
        ];

        $redpackBillData = array_merge($redpackData, $redpackBillData, $result);

        RedPackBill::query()->create($redpackBillData);

        //标记优惠券为已使用-不再发放现金红包
        $coupon->update(['status' => 1]);

        //如果错误 封装成 500, 方便前端处理
        if ($result['result_code'] == 'FAIL') {
//            ding()->with()->text();
            abort(500, $result['return_msg']);
        }

        return $result;
    }

}