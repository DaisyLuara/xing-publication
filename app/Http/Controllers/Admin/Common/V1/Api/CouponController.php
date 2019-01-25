<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Request\UserCouponRequest;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Common\V1\Request\CouponRequest;
use App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Common\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Controller;
use App\Models\WeChatUser;
use Carbon\Carbon;
use DB;
use Log;
use Overtrue\EasySms\EasySms;
use function GuzzleHttp\Psr7\parse_query;


class CouponController extends Controller
{

    /**
     * 优惠券规则 详情
     * @param CouponBatch $couponBatch
     * @return mixed
     */
    public function getCouponBatch(CouponBatch $couponBatch)
    {
        if (!$couponBatch->dmg_status && !$couponBatch->pmg_status && $couponBatch->stock <= 0) {
            abort(500, '优惠券已发完');
        }

        $startOfDay = Carbon::now()->toDateString();
        if ($couponBatch->dmg_status == 0) {
            $coupon = Coupon::query()->where('coupon_batch_id', $couponBatch->id)
                ->whereRaw("date_format(created_at,'%Y-%m-%d')='$startOfDay'")
                ->selectRaw("count(coupon_batch_id) as day_receive")->first();

            if ($coupon->day_receive >= $couponBatch->day_max_get) {
                abort(500, '该券今日已发完，明天再来领取吧！');
            }
        }

        return $this->response->item($couponBatch, new CouponBatchTransformer());

    }

    /**
     * 根据策略 生成优惠券规则
     * @param CouponRequest $request
     * @return mixed
     */
    public function generateCouponBatch(CouponRequest $request)
    {
        //是否已经获取优惠券规则
        $userID = 0;
        //@todo policy 也要添加字段
        if ($request->policy_id && $request->policy_id == 4) {
            $request->validate($request, ['sign' => 'required']);
            $userID = decrypt($request->sign);
            $start = Carbon::now()->startOfDay();
            $end = Carbon::now()->endOfDay();
            $userCouponBatch = UserCouponBatch::query()->where('wx_user_id', $userID)
                ->leftJoin('coupon_batch_policy', 'coupon_batch_policy.coupon_batch_id', '=', 'user_coupon_batches.coupon_batch_id')
                ->where('coupon_batch_policy.policy_id', 4)
                ->whereBetween('user_coupon_batches.created_at', [$start, $end])
                ->first();
            if ($userCouponBatch) {
                $couponBatch = CouponBatch::findOrFail($userCouponBatch->coupon_batch_id);
                $couponBatch->setAttribute('wx_user_id', $userID);
                return $this->response->item($couponBatch, new CouponBatchTransformer());
            }
        }

        $policy = Policy::query()->findOrFail($request->policy_id);

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
        if ($userID) {
            $data = ['wx_user_id' => $userID, 'coupon_batch_id' => $couponBatch->id];
            UserCouponBatch::updateOrCreate($data, $data);
        }

        return $this->response->item($couponBatch, new CouponBatchTransformer());

    }

    /**
     * 2018年11月06日17:04:14
     * 大融城活动
     * 抽奖并减少库存
     * @deprecated 这个接口并没有什么卵用
     */
    public function generateCouponBatchAndDecrement(CouponRequest $request)
    {

        $policy = Policy::query()->findOrFail($request->policy_id);

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

        $couponBatchPolicies = $query->join('coupon_batches', 'coupon_batch_id', '=', 'coupon_batches.id')->where('policy_id', '=', $policy->id)->get();

        if ($couponBatchPolicies->isEmpty()) {
            abort(500, '无可用优惠券');
        }

        $couponBatchPolicies = $couponBatchPolicies->toArray();
        foreach ($couponBatchPolicies as $key => $couponBatchPolicy) {
            if (!$couponBatchPolicy->pmg_status && !$couponBatchPolicy->dmg_status && $couponBatchPolicy->stock <= 0) {
                unset($couponBatchPolicies[$key]);
            }
        }

        if (collect($couponBatchPolicies)->sum('rate') == 0) {
            abort(500, '无可用优惠券');
        }

        $targetCouponBatch = getRand($couponBatchPolicies);
        $couponBatch = CouponBatch::findOrFail($targetCouponBatch->coupon_batch_id);

        if ($couponBatch->stock <= 0) {
            abort(500, '优惠券已发完');
        }

        $couponBatch->decrement('stock');

        return $this->response->item($couponBatch, new CouponBatchTransformer());
    }

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
        $userID = $request->has('sign') ? decrypt($request->get('sign')) : 0;
        $gameAttributePayload = $request->has('game_attribute_payload') ? decrypt($request->get('game_attribute_payload')) : null;
        $gameAttributePayload = parse_query($gameAttributePayload);
        Log::info('game_attribute_payload', $gameAttributePayload);

        abort_if(!isset($gameAttributePayload['coupon_batch_id']), 404, 'coupon batch not found!');
        $couponBatch = CouponBatch::query()->where('is_active', 1)->findOrFail($gameAttributePayload['coupon_batch_id']);
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
            if (in_array($couponBatch->id, [3, 4, 5, 6])) {
                //按微信客户端 发送优惠券(活动期间 限制领取张数)
                $couponBatchIds = [3, 4, 5, 6];
                $coupons = Coupon::query()->where('wx_user_id', $userID)->whereIn('coupon_batch_id', $couponBatchIds)->get();

                $couponBatchId = $this->scoreToCoupon($userID, ['FarmSchool', 'FarmSchoolHigh']);

            } elseif (in_array($couponBatch->id, [7, 8, 9, 10])) {
                //按微信客户端 发送优惠券(活动期间 每天限制领取张数)
                $couponBatchIds = [7, 8, 9, 10];
                $coupons = Coupon::query()->where('wx_user_id', $userID)
                    ->whereIn('coupon_batch_id', $couponBatchIds)
                    ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                    ->get();

                if ($coupons->count() >= $couponBatch->people_max_get) {
                    abort(500, '您今天已经领过了，请明天再来!');
                }

            } else if (in_array($couponBatch->id, [242, 241, 240, 239, 238, 237])) {
                //按微信客户端 发送优惠券(活动期间 每天限制领取张数)
                $couponBatchIds = [242, 241, 240, 239, 238, 237];
                $coupons = Coupon::query()->where('wx_user_id', $userID)
                    ->whereIn('coupon_batch_id', $couponBatchIds)
                    ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                    ->get();

                if ($coupons->count() >= $couponBatch->people_max_get) {
                    abort(500, '您今天已经领过了，请明天再来!');
                }
            } else if ($mobile) {
                //按手机号码 发送优惠券
                Log::info('mobile', $request->all());
                $couponBatchIds = [$couponBatchId];
                $coupons = Coupon::query()->where('mobile', $mobile)->whereIn('coupon_batch_id', $couponBatchIds)->get();
            } else if ($request->has('qiniu_id')) {
                //活动期间 每人每天领取次数
                $coupons = Coupon::query()->where('wx_user_id', $userID)
                    ->where('coupon_batch_id', $couponBatchId)
                    ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                    ->get();

                if ($coupons->count() >= $couponBatch->people_max_get) {
                    abort(500, '您今天已经领过了，请明天再来!');
                }
            } else {
                //根据微信客户端 发送优惠券
                Log::info('wx_user_id', [$userID]);
                $coupons = Coupon::query()->where('wx_user_id', $userID)
                    ->where('coupon_batch_id', $couponBatchId)
                    ->get();

                if ($coupons->count() >= $couponBatch->people_max_get) {
                    abort(500, '您今天已经领过了，请明天再来!');
                }
            }

            if ($coupons->count() >= $couponBatch->people_max_get) {
                abort(500, '优惠券每人最多领取' . $couponBatch->people_max_get . '张');
            }
        }

        if ($couponBatch->third_code) {

            $user = WeChatUser::query()->findOrFail($userID, ['mallcoo_open_user_id']);
            $result = $this->sendMallCooCoupon($user->mallcoo_open_user_id, $couponBatch->third_code);
            if ($result['Code'] != 1) {
                abort(500, $result['Message']);
            }

            if (!$result['Data'][0]['IsSuccess']) {
                abort(500, $result['Data'][0]['FailReason']);
            }

            $data = $result['Data'];
            DB::beginTransaction();
            try {

                $coupon = Coupon::create([
                    'code' => $data[0]['VCode'],
                    'mobile' => $mobile,
                    'coupon_batch_id' => $couponBatch->id,
                    'picm_id' => $data[0]['PICMID'],
                    'trace_id' => $data[0]['TraceID'],
                    'status' => 3,
                    'wx_user_id' => $userID,
                    'qiniu_id' => $gameAttributePayload && isset($gameAttributePayload['id']) ? $gameAttributePayload['id'] : 0,
                    'oid' => $gameAttributePayload && isset($gameAttributePayload['utm_source']) ? $gameAttributePayload['utm_source'] : 0,
                    'belong' => $gameAttributePayload && isset($gameAttributePayload['utm_campaign']) ? $gameAttributePayload['utm_campaign'] : '',
                ]);
                $couponBatch->decrement('stock');
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                abort(500, $e->getMessage());
            }
        } else {

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
                    'wx_user_id' => $userID,
                    'qiniu_id' => $gameAttributePayload && isset($gameAttributePayload['id']) ? $gameAttributePayload['id'] : 0,
                    'oid' => $gameAttributePayload && isset($gameAttributePayload['utm_source']) ? $gameAttributePayload['utm_source'] : 0,
                    'belong' => $gameAttributePayload && isset($gameAttributePayload['utm_campaign']) ? $gameAttributePayload['utm_campaign'] : '',
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
        }


        return $this->response->item($coupon, new CouponTransformer());
    }

    public function getUserCoupon(UserCouponRequest $request)
    {
        $userID = decrypt($request->sign);
        $query = Coupon::query();

        if ($request->has('qiniu_id')) {
            $query->where('qiniu_id', $request->get('qiniu_id'));
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')]);
        }

        $coupon_batch_id = $request->get('coupon_batch_id');
        if (in_array($coupon_batch_id, [242, 241, 240, 239, 238, 237])) {
            $coupon = $query->where('wx_user_id', $userID)
                ->whereIn('coupon_batch_id', [242, 241, 240, 239, 238, 237])
                ->first();
        } else {
            $coupon = $query->where('wx_user_id', $userID)
                ->where('coupon_batch_id', $request->get('coupon_batch_id'))
                ->first();
        }

        abort_if(!$coupon, 204);

        //优惠券二维码
        $wechatCouponBatch = CouponBatch::query()->findOrFail($coupon->coupon_batch_id)->wechat;
        $prefix = 'h5_code';
        $qrcodeUrl = couponQrCode($coupon->code, 200, $prefix, $wechatCouponBatch);
        $coupon->setAttribute('qrcode_url', $qrcodeUrl);

        return $this->response->item($coupon, new CouponTransformer());
    }

    /**
     * 积分兑换
     * FarmSchool  FarmSchoolHigh
     * @param $belongs
     * @return int
     */
    private function scoreToCoupon($userID, $belongs)
    {

        $result = DB::connection('jingsaas')->table('game_result')->whereIn('game_result.belong', $belongs)
            ->leftJoin('game_attribute', 'game_attribute.id', '=', 'game_result.game_attribute_id')
            ->selectRaw('sum(oc_game_attribute.score) as score')
            ->where('user_id', '=', $userID)->first();


        abort_if(!$result->score, 500, '您的积分不足,无法兑换优惠券!');

        if ($result->score >= 0 && $result->score <= 200) {
            return 3;
        } elseif ($result->score <= 400 && $result->score > 200) {
            return 4;
        } elseif ($result->score <= 800 && $result->score > 401) {
            return 5;
        } else {
            return 6;
        }
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
