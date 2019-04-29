<?php

namespace App\Http\Controllers\Admin\Common\V3\Api;

use App\Http\Controllers\Admin\Common\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Admin\Common\V2\Request\UserCouponRequest;
use App\Http\Controllers\Admin\Common\V2\Request\CouponRequest;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberSession;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Log;

class CouponController extends Controller
{
    /**
     * 发送优惠券
     * @param CouponRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CouponRequest $request)
    {
        $member = ArMemberSession::query()->where('z', $request->get('z'))->firstOrFail();
        $memberUID = $member->uid;

        $project = Project::query()->where('versionname', '=', $request->get('belong'))->firstOrFail();
        $policy = Policy::query()->findOrFail($project->policy_id);

        //策略每人抽奖次数校验
        if (!$policy->per_person_unlimit) {
            $couponPerPersonGet = Coupon::query()->where('member_uid', $memberUID)
                ->where('belong', $request->get('belong'))->count();
            abort_if($couponPerPersonGet >= $policy->per_person_times, 500, '优惠券领取数量已达上限!');
        }

        //策略每人每天抽奖次数校验
        if (!$policy->per_person_per_day_unlimit) {
            $couponPerPersonPerDayGet = Coupon::query()->where('member_uid', $memberUID)
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->where('belong', $request->get('belong'))->count();
            abort_if($couponPerPersonPerDayGet >= $policy->per_person_per_day_times, 500, '今日领券数量已达上限,请明天再来!');
        }

        //coupon_batch_id参数校验
        $couponBatch = $member->userCouponBatches()->firstOrFail();
        $couponBatchId = $couponBatch->id;

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
            //活动期间 每人每天领取次数
            $coupons = Coupon::query()->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->where('member_uid', $memberUID)->where('coupon_batch_id', $couponBatchId)->get();

            if ($coupons->count() >= $couponBatch->people_max_get) {
                abort(500, '优惠券每人最多领取' . $couponBatch->people_max_get . '张');
            }
        }

        $code = uniqid('', true);
        //微信卡券二维码
        $wechatCouponBatch = $couponBatch->wechat;
        $prefix = 'h5_code_';

        //券的有效期
        if ($couponBatch->is_fixed_date) {
            $startDate = Carbon::createFromTimeString($couponBatch->start_date);
            $endDate = Carbon::createFromTimeString($couponBatch->end_date);
        } else {
            $startDate = Carbon::now()->addDays($couponBatch->delay_effective_day);
            $endDate = Carbon::now()->addDays($couponBatch->delay_effective_day + $couponBatch->effective_day);
        }

        DB::beginTransaction();
        try {

            $coupon = Coupon::create([
                'code' => $code,
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

            $coupon = $this->setCodeImageUrl($coupon, $prefix, $wechatCouponBatch, $request->code_type);

            //不使用系统核销 领取优惠券后 ，自动减去库存
            if (!$couponBatch->write_off_status && !$couponBatch->pmg_status && !$couponBatch->pmg_status) {
                $couponBatch->decrement('stock');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();//事务回滚
            abort(500, $e->getMessage());
        }

        return $this->response->item($coupon, new CouponTransformer());
    }

    public function getUserCoupon(UserCouponRequest $request)
    {
        $member = ArMemberSession::query()->where('z', $request->get('z'))->firstOrFail();

        $query = Coupon::query();

        if ($request->has('qiniu_id')) {
            $query->where('qiniu_id', $request->get('qiniu_id'));
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')]);
        }

        if ($request->has('coupon_batch_id')) {
            $query->where('coupon_batch_id', $request->get('coupon_batch_id'));
        }

        if ($request->has('belong')) {
            $query->where('belong', $request->get('belong'));
        }

        $coupon = $query->where('member_uid', $member->uid)->first();

        abort_if(!$coupon, 204);

        //优惠券二维码
        $wechatCouponBatch = CouponBatch::query()->findOrFail($coupon->coupon_batch_id)->wechat;
        $prefix = 'h5_code_';

        $coupon = $this->setCodeImageUrl($coupon, $prefix, $wechatCouponBatch, $request->code_type);

        return $this->response->item($coupon, new CouponTransformer());
    }

    /**
     * 设置券码url
     * @param $coupon
     * @param string $code_type
     * @return mixed
     */
    private function setCodeImageUrl($coupon, $prefix, $wechatCouponBatch = null, $code_type = 'qrcode')
    {
        if ($code_type === 'barcode') {
            $barcodeUrl = couponBarCode($coupon->code, 2, 180, $prefix);
            $coupon->setAttribute('barcode_url', $barcodeUrl);
        } else {
            $qrcodeUrl = couponQrCode($coupon->code, 200, $prefix, $wechatCouponBatch);
            $coupon->setAttribute('qrcode_url', $qrcodeUrl);
        }

        return $coupon;
    }

}
