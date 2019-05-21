<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Models\FileUpload;
use App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch;
use App\Http\Controllers\Admin\MallCoo\V1\Transformer\CouponPackTransformer;

use App\Http\Controllers\Admin\Common\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Admin\MallCoo\V1\Request\CouponPackRequest;
use App\Http\Controllers\Admin\MallCoo\V1\Request\CouponRequest;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Traits\CouponBatch as CouponBatchTrait;
use Carbon\Carbon;
use DB;

class CouponController extends BaseController
{
    use CouponBatchTrait;

    /**
     * 发送优惠券礼包
     * @param CouponPackRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function generateCouponPacks(CouponPackRequest $request)
    {
        $wxUserId = decrypt($request->get('sign'));

        $project = Project::query()->where('versionname', '=', $request->get('belong'))->firstOrFail();
        $policy = Policy::query()->findOrFail($project->policy_id);

        //策略券礼包每人领取次数校验
        if (!$policy->per_person_unlimit) {
            $couponPackPerPersonGet = UserPolicy::query()->where('wx_user_id', $wxUserId)
                ->where('belong', $request->get('belong'))->count();
            abort_if($couponPackPerPersonGet >= $policy->per_person_times, 429, '领取礼包数量已达上限!');
        }

        //策略券礼包每人每天领取次数校验
        if (!$policy->per_person_per_day_unlimit) {
            $couponPackPerPersonPerDayGet = UserPolicy::query()->where('wx_user_id', $wxUserId)
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->where('belong', $request->get('belong'))->count();
            abort_if($couponPackPerPersonPerDayGet >= $policy->per_person_per_day_times, 429, '今日领取礼包已达上限,请明天再来!');
        }

        //获取会员信息
        /** @var ThirdPartyUser $user */
        $user = ThirdPartyUser::query()->where('wx_user_id', $wxUserId)
            ->where('marketid', $this->mall_coo->marketid)->first();

        $couponBatches = $policy->batches;

        DB::beginTransaction();

        try {
            foreach ($couponBatches as $couponBatch) {
                //调用猫酷券接口
                $result = $this->mall_coo->sendCouponByOpenUserID($user->mallcoo_open_user_id, $couponBatch->third_code);
                abort_if($result['Code'] !== 1, 500, $result['Message']);
                abort_if(!$result['Data'][0]['IsSuccess'], 500, $result['Data'][0]['FailReason']);

                $data = $result['Data'];
                Coupon::query()->create([
                    'code' => $data[0]['VCode'],
                    'coupon_batch_id' => $couponBatch->id,
                    'picm_id' => $data[0]['PICMID'],
                    'trace_id' => $data[0]['TraceID'],
                    'status' => 3,
                    'wx_user_id' => $wxUserId,
                    'qiniu_id' => $request->get('qiniu_id') ?? 0,
                    'oid' => $request->get('oid'),
                    'belong' => $request->get('belong') ?? '',
                    'utm_source' => 1,
                    'start_date' => Carbon::createFromTimeString($couponBatch->start_date),
                    'end_date' => Carbon::createFromTimeString($couponBatch->end_date),
                ]);

                $couponBatch->decrement('stock');
            }

            UserPolicy::query()->create([
                'wx_user_id' => $wxUserId,
                'qiniu_id' => $request->get('qiniu_id'),
                'policy_id' => $policy->id,
                'belong' => $request->get('belong'),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();//事务回滚
            abort(500, $e->getMessage());
        }

        return $this->response->collection($couponBatches, new CouponPackTransformer());
    }


    /**
     * 查看优惠券礼包
     * @param CouponPackRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function getUserCouponPacks(CouponPackRequest $request)
    {
        $wxUserId = decrypt($request->get('sign'));

        $query = UserPolicy::query();
        /** @var FileUpload $fileUpload */
        $fileUpload = FileUpload::query()->findOrFail($request->qiniu_id);

        $userPolicy = $query->where('wx_user_id', $wxUserId)
            ->whereDate('created_at', Carbon::parse($fileUpload->date)->toDateString())
            ->where('belong', $request->get('belong'))
            ->first();

        abort_if(!$userPolicy, 204);
        $couponBatches = $userPolicy->policy->batches;

        return $this->response->collection($couponBatches, new CouponPackTransformer());
    }

    /**
     * 发送优惠券
     * @param CouponRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CouponRequest $request)
    {
        $wxUserId = decrypt($request->get('sign'));

        $coupon = Coupon::query()->where('wx_user_id', $wxUserId)
            ->where('belong', $request->get('belong'))->get();
        abort_if($coupon->isNotEmpty(), 500, '请勿重复领取');

        //获取用户券规则
        $userCouponBatch = UserCouponBatch::query()->where('wx_user_id', $wxUserId)->where('belong', $request->get('belong'))->firstOrFail();
        $couponBatch = $userCouponBatch->couponBatch;
        $couponBatchId = $couponBatch->id;

        //库存校验
        if (!$couponBatch->dmg_status && !$couponBatch->pmg_status && $couponBatch->stock <= 0) {
            abort(500, '优惠券已发完!');
        }

        //当天库存校验
        if (!$couponBatch->dmg_status) {
            $coupon = Coupon::query()->where('coupon_batch_id', $couponBatchId)
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->selectRaw('count(coupon_batch_id) as day_receive')
                ->first();
            abort_if($coupon->day_receive >= $couponBatch->day_max_get, 500, '该券今日已发完，明天再来领取吧！');
        }

        //每人每天库存校验
        if (!$couponBatch->pmg_status) {
            $coupons = Coupon::query()->whereDate('created_at', Carbon::now()->toDateString())
                ->where('wx_user_id', $wxUserId)->where('coupon_batch_id', $couponBatchId)
                ->get();
            abort_if($coupons->count() >= $couponBatch->people_max_get, 500, '优惠券每人最多领取' . $couponBatch->people_max_get . '张');
        }

        $code = uniqid();

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

            $coupon = Coupon::query()->create([
                'code' => $code,
                'coupon_batch_id' => $couponBatchId,
                'status' => 3,
                'wx_user_id' => $wxUserId,
                'qiniu_id' => $request->get('qiniu_id') ?? 0,
                'oid' => $request->get('oid'),
                'utm_source' => 1,
                'belong' => $request->get('belong') ?? '',
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            //不使用系统核销 领取优惠券后 ，自动减去库存
            if (!$couponBatch->write_off_status && !$couponBatch->pmg_status && !$couponBatch->pmg_status) {
                $couponBatch->decrement('stock');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();//事务回滚
            abort(500, $e->getMessage());
        }

        //发送券码短信
        $this->sendCouponMsg($coupon, $wxUserId, $this->mall_coo->marketid);

        return $this->response->item($coupon, new CouponTransformer());
    }

    /**
     * 查询优惠券
     * @param CouponRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function show(CouponRequest $request)
    {
        $wxUserId = decrypt($request->get('sign'));
        $query = Coupon::query();

        if ($request->has('belong')) {
            $query->where('belong', $request->get('belong'));
        }

        $coupon = $query->where('wx_user_id', $wxUserId)->first();
        abort_if(!$coupon, 204);

        return $this->response->item($coupon, new CouponTransformer());
    }

}
