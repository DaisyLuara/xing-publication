<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Api;

use App\Http\Controllers\Admin\MallCoo\V1\Transformer\CouponPackTransformer;
use App\Http\Controllers\Admin\MallCoo\V1\Request\CouponRequest;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Coupon\V1\Models\UserPolicy;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class CouponController extends BaseController
{

    /**
     * 获取该商场下优惠分类列表
     */
    public function index()
    {
        $sUrl = 'https://openapi10.mallcoo.cn/Coupon/PutIn/v1/GetAll/';
        $result = $this->mall_coo->send($sUrl);
        abort_if($result['Code'] !== 1, 500, $result['Message']);
        return response()->json($result['Data']);

    }

    /**
     * 发送优惠券礼包
     * @param CouponRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function generateCouponPacks(CouponRequest $request)
    {
        $wxUserId = decrypt($request->get('sign'));

        $project = Project::query()->where('versionname', '=', $request->get('belong'))->firstOrFail();
        $policy = Policy::query()->findOrFail($project->policy_id);

        //策略券礼包每人领取次数校验
        if (!$policy->per_person_unlimit) {
            $couponPackPerPersonGet = UserPolicy::query()->where('wx_user_id', $wxUserId)
                ->where('belong', $request->get('belong'))->count();
            abort_if($couponPackPerPersonGet >= $policy->per_person_times, 500, '领取礼包数量已达上限!');
        }

        //策略券礼包每人每天领取次数校验
        if (!$policy->per_person_per_day_unlimit) {
            $couponPackPerPersonPerDayGet = UserPolicy::query()->where('wx_user_id', $wxUserId)
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->where('belong', $request->get('belong'))->count();
            abort_if($couponPackPerPersonPerDayGet >= $policy->per_person_per_day_times, 500, '今日领取礼包已达上限,请明天再来!');
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
     * @param CouponRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function getUserCouponPacks(CouponRequest $request)
    {
        $wxUserId = decrypt($request->get('sign'));

        $userPolicy = UserPolicy::query()->where('wx_user_id', $wxUserId)
            ->where('belong', $request->get('belong'))
            ->first();

        abort_if(!$userPolicy, 204);
        $couponBatches = $userPolicy->policy->batches;

        return $this->response->collection($couponBatches, new CouponPackTransformer());
    }


    /**
     * 发送优惠券
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'open_user_id' => 'required',
            'pic_mid' => 'required',
        ]);

        $sUrl = 'https://openapi10.mallcoo.cn/Coupon/v1/Send/ByOpenUserID/';

        /**
         * @todo 根据封装成 可以根据手机号码 微信id 发送的形式
         */
        $data = [
            'UserList' => [
                [
                    'BussinessID' => null,
                    'TraceID' => uniqid('', true) . config('mall_coo.app_id'),
                    'PICMID' => $request->get('pic_mid'),
                    'OpenUserID' => $request->get('open_user_id'),
                ]
            ]
        ];

        $result = $this->mall_coo->send($sUrl, $data);
        abort_if($result['Code'] !== 1, 500, $result['Message']);

        /**
         * mallcoo 本身提供的是批量接口
         * 目前业务需要 每次只发送一张优惠券
         */
        if (!$result['Data'][0]['IsSuccess']) {
            abort(500, $result['Data'][0]['FailReason']);
        }

        return response()->json($result['Data'][0], 'Send Coupon Success!');

    }

}
