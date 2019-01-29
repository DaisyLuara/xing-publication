<?php

namespace App\Http\Controllers\Admin\Common\V2\Api;

use App\Http\Controllers\Admin\Common\V1\Models\FileUpload;
use App\Http\Controllers\Admin\Common\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Admin\Common\V2\Request\UserCouponRequest;
use App\Http\Controllers\Admin\Common\V1\Request\CouponRequest;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberSession;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Controller;
use Overtrue\EasySms\EasySms;
use App\Models\WeChatUser;
use Carbon\Carbon;
use DB;
use Log;


class CouponController extends Controller
{
    /**
     * 发送优惠券
     * @param CouponBatch $couponBatch
     * @param CouponRequest $request
     * @param EasySms $easySms
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateCoupon(CouponRequest $request, EasySms $easySms)
    {
        $mobile = $request->has('mobile') ? $request->get('mobile') : '';

        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();
        $memberUID = $member->uid;

        //coupon_batch_id参数校验
        $fileUpload = FileUpload::query()->findOrFail($request->qiniu_id);
        parse_str($fileUpload->parms, $parms_arr);
        abort_if(!isset($parms_arr['coupon_batch_id']), 404, 'coupon batch not found!');

        $couponBatch = CouponBatch::query()->where('is_active', 1)->findOrFail($parms_arr['coupon_batch_id']);
        $couponBatchId = $couponBatch->id;

        //动态库存校验
        if ($couponBatch->dynamic_stock_status) {
            $count = Coupon::query()->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->whereIn('status', [0, 3])
                ->where('coupon_batch_id', $couponBatch->id)->count('id');
            $dynamicStock = $couponBatch->stock - $count;
            abort_if($dynamicStock <= 0, 500, '优惠券已发完!');

        }

        //库存校验
        if (!$couponBatch->dmg_status && !$couponBatch->pmg_status && $couponBatch->stock <= 0) {
            abort(500, '优惠券已发完!');
        }

        //当天库存校验
        $now = Carbon::now()->toDateString();
        if (!$couponBatch->dmg_status) {
            $coupon = Coupon::query()->where('coupon_batch_id', $couponBatchId)
                ->whereRaw("date_format(created_at,'%Y-%m-%d')='$now'")
                ->selectRaw("count(coupon_batch_id) as day_receive")->first();

            if ($coupon->day_receive >= $couponBatch->day_max_get) {
                abort(500, '该券今日已发完，明天再来领取吧！');
            }
        }

        //每人库存校验
        if (!$couponBatch->pmg_status) {
            if ($mobile) {
                //按手机号码 发送优惠券
                Log::info('mobile', $request->all());
                $couponBatchIds = [$couponBatchId];
                $coupons = Coupon::query()->where('mobile', $mobile)->whereIn('coupon_batch_id', $couponBatchIds)->get();

            } else if ($request->has('qiniu_id')) {
                //活动期间 每人每天领取次数
                $coupons = Coupon::query()->where('member_uid', $memberUID)
                    ->where('coupon_batch_id', $couponBatchId)
                    ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                    ->get();
            }

            if ($coupons->count() >= $couponBatch->people_max_get) {
                abort(500, '优惠券每人最多领取' . $couponBatch->people_max_get . '张');
            }
        }

        $code = uniqid();
        //微信卡券二维码
        $wechatCouponBatch = $couponBatch->wechat;
        $prefix = 'h5_code';
        $qrcodeUrl = couponQrCode($code, 200, $prefix, $wechatCouponBatch);

        //券的有效期
        if ($couponBatch->is_fixed_date) {
            $startDate = Carbon::createFromTimeString($couponBatch->start_date);;
            $endDate = Carbon::createFromTimeString($couponBatch->end_date);
        } else {
            $startDate = Carbon::now()->addDays($couponBatch->delay_effective_day);
            $endDate = Carbon::now()->addDays($couponBatch->delay_effective_day + $couponBatch->effective_day);
        }

        DB::beginTransaction();
        try {

            $coupon = Coupon::create([
                'code' => $code,
                'mobile' => $mobile,
                'coupon_batch_id' => $couponBatchId,
                'status' => 3,
                'member_uid' => $memberUID,
                'qiniu_id' => $request->qiniu_id ?? 0,
                'oid' => $request->oid,
                'utm_source' => 1,
                'belong' => $request->belong ?? '',
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);


            $coupon->setAttribute('qrcode_url', $qrcodeUrl);

            //不使用系统核销 领取优惠券后 ，自动减去库存
            if (!$couponBatch->write_off_status && !$couponBatch->pmg_status && !$couponBatch->pmg_status) {

                $couponBatch->decrement('stock');
            }

            DB::commit();

            if ($mobile) {
                $this->sendCouponMsg($mobile, $couponBatch, $easySms);
            }
        } catch (\Exception $e) {
            DB::rollback();//事务回滚
            abort(500, $e->getMessage());
        }
    

        return $this->response->item($coupon, new CouponTransformer());
    }

    public function getUserCoupon(UserCouponRequest $request)
    {
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();

        $query = Coupon::query();

        if ($request->has('qiniu_id')) {
            $query->where('qiniu_id', $request->get('qiniu_id'));
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')]);
        }

        $coupon = $query->where('member_uid', $member->uid)
            ->where('coupon_batch_id', $request->get('coupon_batch_id'))
            ->first();

        abort_if(!$coupon, 204);

        //优惠券二维码
        $wechatCouponBatch = CouponBatch::query()->findOrFail($coupon->coupon_batch_id)->wechat;
        $prefix = 'h5_code';
        $qrcodeUrl = couponQrCode($coupon->code, 200, $prefix, $wechatCouponBatch);
        $coupon->setAttribute('qrcode_url', $qrcodeUrl);

        return $this->response->item($coupon, new CouponTransformer());
    }

    private function sendMallCooCoupon($open_user_id, $picmID)
    {
        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/Coupon/v1/Send/ByOpenUserID/';

        $data = [
            'UserList' => [
                [
                    'BussinessID' => null,
                    'TraceID' => uniqid() . config('mall_coo.app_id'),
                    'PICMID' => $picmID,
                    'OpenUserID' => $open_user_id,
                ]
            ]
        ];

        return $mall_coo->send($sUrl, $data);

    }

    private function sendCouponMsg($mobile, CouponBatch $couponBatch, EasySms $easySms)
    {
        if (!app()->environment('production')) {
            $allowed = [15921145624, 13818403072, 13052361619, 18616348089, 15856363087, 16602119264];
            if (!in_array($mobile, $allowed)) {
                abort(422, '非法手机号码');
            }
        }

        switch ($couponBatch->name) {
            case '平安符':
                $content = "【星视度】恭喜您获得“平安符”一枚，凭此短信到服务台免费领取，快快领取使用吧。";
                break;
            case '西树泡芙5元代金券':
                $content = '【星视度】恭喜您获得“西树泡芙5元代金券”，凭此短信到服务台免费领取。使用期限10月31日，快快领取使用吧。';
                break;
            case '熊本熊水杯':
                $content = '【星视度】恭喜您获得“熊本熊水杯”一个，凭此短信到服务台免费领取，快快领取使用吧。';
                break;
            case '苏小柳100元代金券':
                $content = '【星视度】恭喜您获得“苏小柳100元代金券”，凭此短信到服务台免费领取。使用期限10月31日，快快领取使用吧。';
                break;
            case '汤姆熊币':
                $content = '【星视度】感谢参与！您获得“汤姆熊币一份”，凭此短信和中奖截图兑换奖品！礼品当天兑换，先到先得！兑奖地点：1楼西庭。';
                break;
            case '炫彩杯子或背包任选':
                $content = '【星视度】感谢参与！您获得“杯子或包一份”，凭此短信和中奖截图兑换奖品！礼品当天兑换，先到先得！兑奖地点：华为门店L119-2。';
                break;
            case '鲜肉月饼':
                $content = '【星视度】感谢参与！您获得“鲜肉月饼一份”，凭此短信和中奖截图兑换奖品！礼品当天兑换，先到先得！兑奖地点：嘉庭L503-2。';
                break;
            case '精美文具礼盒':
                $content = '【星视度】感谢参与！您获得“文具礼盒一份”，凭此短信和中奖截图兑换奖品！礼品当天兑换，先到先得！兑奖地点：Balabala 童装店L324-325。';
                break;
            default:
                return;
        }

        try {
            $result = $easySms->send($mobile, [
                'content' => $content,
            ]);
            Log::info('send_coupon_msg', $result);

        } catch (\Exception $exception) {
            Log::info('send_msg_exceptions', ['msg' => $exception->getMessage()]);
        }

    }

}
