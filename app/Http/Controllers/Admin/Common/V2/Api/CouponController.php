<?php

namespace App\Http\Controllers\Admin\Common\V2\Api;

use App\Http\Controllers\Admin\Common\V1\Models\FileUpload;
use App\Http\Controllers\Admin\Common\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Admin\Common\V2\Request\UserCouponRequest;
use App\Http\Controllers\Admin\Common\V2\Request\CouponRequest;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberSession;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Http\Controllers\Controller;
use Milon\Barcode\DNS1D;
use Overtrue\EasySms\EasySms;
use Illuminate\Http\Request;
use App\Models\User;
use function foo\func;
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
    public function generateCoupon(CouponRequest $request, CouponBatch $couponBatch, $multiProjects = false)
    {
        $mobile = $request->has('mobile') ? $request->get('mobile') : '';

        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();
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
        if ($multiProjects) {
            //多节目集齐勋章或者h5抽奖
            $couponBatch = $member->userCouponBatches()->firstOrFail();

        } else {
            $fileUpload = FileUpload::query()->findOrFail($request->qiniu_id);
            parse_str($fileUpload->parms, $parms_arr);
            abort_if(!isset($parms_arr['coupon_batch_id']), 404, 'coupon batch not found!');
            $couponBatch = CouponBatch::query()->where('is_active', 1)->findOrFail($parms_arr['coupon_batch_id']);
        }

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
                $query = Coupon::query();

                $coupons = $query->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                    ->where('member_uid', $memberUID)->where('coupon_batch_id', $couponBatchId)->get();
            }

            if ($coupons->count() >= $couponBatch->people_max_get) {
                abort(500, '优惠券每人最多领取' . $couponBatch->people_max_get . '张');
            }
        }

        if ($couponBatch->third_code) {
            return $this->generateMallCooCoupon($request, $couponBatch, $memberUID);
        }

        $code = uniqid();
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
                'mobile' => $mobile,
                'coupon_batch_id' => $couponBatchId,
                'status' => 3,
                'member_uid' => $memberUID,
                'qiniu_id' => $request->qiniu_id ?? 0,
                'oid' => $request->oid,
                'utm_source' => 1,
                'belong' => $request->belong ?? '',
                'ser_timestamp' => $request->get('ser_timestamp'),
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
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();

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

        if ($request->has('ser_timestamp')) {
            $query->where('ser_timestamp', $request->get('ser_timestamp'));
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
     * 根据策略 生成优惠券规则
     * @param CouponRequest $request
     * @return mixed
     */
    public function generateCouponBatch(Request $request)
    {
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();

        abort_if($member->userCouponBatches->isNotEmpty(), '500', '请勿重复抽奖');

        //用户成就校验
        foreach ([11, 12, 13] as $id) {
            $arMemberHonor = ArMemberHonor::query()->where('uid', $member->uid)->where('xid', $id)->first();
            abort_if(!$arMemberHonor, 500, '请集齐勋章后再抽奖!');
        }

        $project = Project::query()->where('versionname', '=', $request->belong)->firstOrFail();
        $policy = Policy::query()->findOrFail($project->policy_id);

        $query = DB::table('coupon_batch_policy');
        if ($request->has('age')) {
            $query->where('max_age', '>=', $request->age)->where('min_age', '<=', $request->age);
        }

        if ($request->has('score')) {
            $query->where('max_score', '>=', $request->score)->where('min_score', '<=', $request->score);
        }

        if ($request->has('gender')) {
            $query->where('gender', '=', $request->gender);
        }

        $couponBatchPolicies = $query->join('coupon_batches', 'coupon_batch_id', '=', 'coupon_batches.id')
            ->where('policy_id', '=', $policy->id)
            ->where('coupon_batches.is_active', 1)
            ->get();

        if ($couponBatchPolicies->isEmpty()) {
            abort(500, '无可用优惠券');
        }

        $couponBatchPolicies = $couponBatchPolicies->toArray();

        /**
         * @todo 优化查询逻辑
         */
        foreach ($couponBatchPolicies as $key => $couponBatchPolicy) {

            //设置了库存上限的券
            if (!$couponBatchPolicy->pmg_status && !$couponBatchPolicy->dmg_status) {

                //剩余库存为0 不出券
                Log::info('coupon_batch_id:' . $couponBatchPolicy->id . ':current_stock:' . $couponBatchPolicy->stock, []);
                if ($couponBatchPolicy->stock <= 0) {
                    unset($couponBatchPolicies[$key]);
                    continue;
                }

                //动态库存=剩余库存-未使用
                if ($couponBatchPolicy->dynamic_stock_status) {
                    $count = Coupon::query()->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                        ->whereIn('status', [0, 3])
                        ->where('coupon_batch_id', $couponBatchPolicy->id)->count('id');
                    $dynamicStock = $couponBatchPolicy->stock - $count;
                    Log::info('coupon_batch_id:' . $couponBatchPolicy->id . ':dynamic_stock:' . $dynamicStock, []);
                    if ($dynamicStock <= 0) {
                        unset($couponBatchPolicies[$key]);
                        continue;
                    }

                }

                //当天库存为0 不出券
                $now = Carbon::now()->toDateString();
                $coupon = Coupon::query()->where('coupon_batch_id', $couponBatchPolicy->id)
                    ->whereRaw("date_format(created_at,'%Y-%m-%d')='$now'")
                    ->selectRaw("count(coupon_batch_id) as day_receive")->first();

                Log::info('coupon_batch_id:' . $couponBatchPolicy->id . ':daily_stock:' . $coupon->day_receive, []);
                if ($coupon->day_receive >= $couponBatchPolicy->day_max_get) {
                    unset($couponBatchPolicies[$key]);
                }
            }
        }

        if (collect($couponBatchPolicies)->sum('rate') == 0) {
            abort(500, '无可用优惠券');
        }

        $targetCouponBatch = getRand($couponBatchPolicies);

        $couponBatch = CouponBatch::findOrFail($targetCouponBatch->coupon_batch_id);
        UserCouponBatch::updateOrCreate(
            ['member_uid' => $member->uid], ['coupon_batch_id' => $couponBatch->id]
        );

        return $this->response->item($couponBatch, new CouponBatchTransformer());

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


    /**
     * h5生成限制条件优惠券规则(扫码h5抽奖)
     * @param CouponRequest $request
     * @return mixed
     */
    public function generateLimitCouponBatch(Request $request)
    {
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();
        $project = Project::query()->where('versionname', '=', $request->belong)->firstOrFail();

        $query = DB::table('coupon_batch_policy');
        if ($request->has('age')) {
            $query->where('max_age', '>=', $request->age)->where('min_age', '<=', $request->age);
        }

        if ($request->has('score')) {
            $query->where('max_score', '>=', $request->score)->where('min_score', '<=', $request->score);
        }

        if ($request->has('gender')) {
            $query->where('gender', '=', $request->gender);
        }

        $couponBatchPolicies = $query->join('coupon_batches', 'coupon_batch_id', '=', 'coupon_batches.id')
            ->where('policy_id', '=', $project->policy_id)
            ->where('coupon_batches.is_active', 1)
            ->get();

        if ($couponBatchPolicies->isEmpty()) {
            abort(500, '无可用优惠券');
        }

        $couponBatchIDs = [];
        $couponBatchPolicies = $couponBatchPolicies->each(static function ($item) use (&$couponBatchIDs) {
            $couponBatchIDs[] = $item->coupon_batch_id;
        });

        //库存校验
        $now = Carbon::now()->toDateString();
        $couponsDayGetQuery = Coupon::query()->whereIn('coupon_batch_id', $couponBatchIDs)
            ->whereRaw("date_format(created_at,'%Y-%m-%d')='$now'")
            ->selectRaw('coupon_batch_id, count(*) as day_receive')
            ->groupBy('coupon_batch_id');

        $couponsPersonGetQuery = clone $couponsDayGetQuery;
        $couponsPersonGetQuery->where('member_uid', $member->uid);

        //当天领取数量
        $couponsDayGetArray = array_column($couponsDayGetQuery->get()->toArray(), 'day_receive', 'coupon_batch_id');
        //每人每天领取数量
        $couponsPersonGetArray = array_column($couponsPersonGetQuery->get()->toArray(), 'day_receive', 'coupon_batch_id');

        foreach ($couponBatchPolicies as $key => $couponBatchPolicy) {
            $couponBatchID = $couponBatchPolicy->coupon_batch_id;
            //不符合条件的优惠券
            if (!$couponBatchPolicy->pmg_status && !$couponBatchPolicy->dmg_status && $couponBatchPolicy->stock <= 0) {
                unset($couponBatchPolicies[$key]);
                continue;
            }

            //当天库存校验
            if (!$couponBatchPolicy->dmg_status && array_key_exists($couponBatchID, $couponsDayGetArray)
                && $couponBatchPolicy->day_max_get <= $couponsDayGetArray[$couponBatchID]) {
                unset($couponBatchPolicies[$key]);
                continue;
            }

            //每人每天库存校验
            if (!$couponBatchPolicy->pmg_status && array_key_exists($couponBatchID, $couponsPersonGetArray)
                && $couponBatchPolicy->people_max_get <= $couponsPersonGetArray[$couponBatchID]) {
                unset($couponBatchPolicies[$key]);
                continue;
            }
        }

        if (collect($couponBatchPolicies)->sum('rate') === 0) {
            abort(500, '无可用优惠券');
        }

        $targetCouponBatch = getRand($couponBatchPolicies);

        $couponBatch = CouponBatch::findOrFail($targetCouponBatch->coupon_batch_id);

        UserCouponBatch::create([
            'member_uid' => $member->uid,
            'coupon_batch_id' => $couponBatch->id,
            'belong' => $request->belong,
        ]);

        return $this->response->item($couponBatch, new CouponBatchTransformer());

    }

    /**
     * h5独立发放优惠券
     * @param CouponRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateMultiProjectsCoupon(CouponRequest $request, CouponBatch $couponBatch)
    {
        return $this->generateCoupon($request, $couponBatch, $multiProjects = true);
    }


    /**
     * 设置券码url
     * @param $coupon
     * @param string $code_type
     * @return mixed
     */
    private function setCodeImageUrl($coupon, $prefix, $wechatCouponBatch = null, $code_type = 'qrcode')
    {
        if ($code_type == 'barcode') {
            $barcodeUrl = couponBarCode($coupon->code, 2, 180, $prefix);
            $coupon->setAttribute('barcode_url', $barcodeUrl);
        } else {
            $qrcodeUrl = couponQrCode($coupon->code, 200, $prefix, $wechatCouponBatch);
            $coupon->setAttribute('qrcode_url', $qrcodeUrl);
        }

        return $coupon;
    }

    /**
     * 发送猫酷商场券
     * @param $request
     * @param $couponBatch
     * @return \Dingo\Api\Http\Response
     */
    private function generateMallCooCoupon($request, $couponBatch, $member_uid)
    {
        //获取会员信息
        $point = Point::query()->findOrFail($request->oid);
        $thirdPartyUser = ThirdPartyUser::query()->where('z', $request->z)
            ->where('marketid', $point->market->marketid)->firstOrFail();

        //调用猫酷券接口
        $result = app('mall_coo')->setMallCooConfig($request->oid)->sendCouponByOpenUserID($thirdPartyUser->mallcoo_open_user_id, $couponBatch->third_code);
        abort_if($result['Code'] != 1, 500, $result['Message']);
        abort_if(!$result['Data'][0]['IsSuccess'], 500, $result['Data'][0]['FailReason']);

        $data = $result['Data'];
        $coupon = Coupon::create([
            'code' => $data[0]['VCode'],
            'coupon_batch_id' => $couponBatch->id,
            'picm_id' => $data[0]['PICMID'],
            'trace_id' => $data[0]['TraceID'],
            'status' => 3,
            'member_uid' => $member_uid,
            'qiniu_id' => $request->qiniu_id ?? 0,
            'oid' => $request->oid,
            'belong' => $request->belong ?? '',
            'utm_source' => 1,
            'start_date' => Carbon::createFromTimeString($couponBatch->start_date),
            'end_date' => Carbon::createFromTimeString($couponBatch->end_date),
        ]);

        $couponBatch->decrement('stock');

        return $this->response->item($coupon, new CouponTransformer());
    }

}
