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
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Controller;
use App\Jobs\RedpackJob;
use App\Models\WeChatUser;
use EasyWeChat;
use App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill;

class RedPackController extends Controller
{
    public function store($code, RedPackRequest $request)
    {
        $userID = $request->has('sign') ? decrypt($request->get('sign')) : 0;
        $wxUser = WeChatUser::findOrFail($userID);
        $coupon = Coupon::query()->where('code', $code)->where('wx_user_id', $userID)->firstOrFail();
        $couponBatch = CouponBatch::query()->where('id', $coupon->coupon_batch_id)->firstOrFail();

        //已经发送过红包
        abort_if($coupon->status == 1, 500, '已经发送过红包了');

        //标记优惠券为已使用-不再发放现金红包
        $coupon->update(['status' => 1]);

        $redPackData = [
            'send_name' => $couponBatch->amount . '元',
            're_openid' => $wxUser->openid,
            'total_num' => 1,
            'total_amount' => $couponBatch->amount,
            'wishing' => '新年快乐!',
            'act_name' => '刮卡抽奖！',
            'remark' => '发送给用户 ' . $wxUser->nickname,
            'scene_id' => 'PRODUCT_2',
            'coupon_batch_id' => $coupon->coupon_batch_id,
            'coupon_code' => $coupon->code,
        ];

        RedpackJob::dispatch($redPackData)->onQueue('redpack');

    }

}