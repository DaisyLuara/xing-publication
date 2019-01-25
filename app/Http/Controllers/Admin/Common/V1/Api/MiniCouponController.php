<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Activity\V1\Models\ActivityCouponBatch;
use App\Http\Controllers\Admin\Common\V1\Models\FileUpload;
use App\Http\Controllers\Admin\Common\V1\Models\XsCreditRecord;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Common\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Admin\Common\V1\Request\MiniCouponRequest;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\UserActivation;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberSession;
use function GuzzleHttp\Psr7\parse_query;
use App\Http\Controllers\Controller;
use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Log;
use DB;


class MiniCouponController extends Controller
{
    /**
     * 优惠券详情
     * @param $code
     * @param Request $request
     * @return mixed
     */
    public function couponShow($code, ImageUploadHandler $uploader, Request $request)
    {
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();
        $couponQuery = Coupon::query();
        $coupon = $couponQuery->where('member_uid', $member->uid)->where('code', $code)->firstOrFail();

        $prefix = 'mini_qrcode_' . $coupon->code;
        $size = $request->size ? $request->size : 200;
        $qrcodeUrl = couponQrCode($coupon->code, $size, $prefix);

        $coupon->setAttribute('qrcode_url', $qrcodeUrl);

        return $this->response->item($coupon, new CouponTransformer());

    }

    /**
     * 用户优惠券列表
     * @param Request $request
     * @return mixed
     */
    public function couponIndex(MiniCouponRequest $request)
    {
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();

        $couponQuery = Coupon::query();
        if ($request->has('status')) {
            $now = Carbon::now()->toDateTimeString();

            switch ($request->get('status')) {
                case 0:
                case 1:
                case 2:
                    $couponQuery->where('status', $request->get('status'));
                    break;
                case 3:
                    //可使用卡券
                    $couponQuery->where('status', $request->get('status'))
                        ->where('end_date', '>', $now);
                    break;
                case 4:
                    //已过期卡券
                    $couponQuery->where('status', '=', '3')
                        ->where('end_date', '<', $now);
                    break;
                default:
                    return null;
            }
        }

        if ($request->has('coupon_batch_id')) {
            $couponQuery->where('coupon_batch_id', $request->get('coupon_batch_id'));
        }

        $coupon = $couponQuery->where('member_uid', $member->uid)->orderByDesc('id')->paginate(10);

        return $this->response->paginator($coupon, new CouponTransformer());
    }

    /**
     * 获取可用 优惠券规则列表
     */
    public function couponBatchesIndex(MiniCouponRequest $request, CouponBatch $couponBatch)
    {
        /**
         * @todo  多个商户参加活动 优惠券配置
         * 新增字段 campaign_id 硬编码 获取活动ID为1的优惠券
         */
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();

        $query = $couponBatch->query();

        if ($request->has('scene')) {
            $scene = $request->scene;
            $scene = str_replace('istart:', '', $scene);
            $id = explode('_', $scene)[0];
            $fileUpload = FileUpload::query()->findOrFail($id);

            abort_if(!$fileUpload->acid, 500, '无效活动');
            $query->whereHas('activityCouponBatches', function ($q) use ($fileUpload) {
                $q->where('activity_id', $fileUpload->acid);
            });
        }

        if ($request->has('acid')) {
            $query->whereHas('activityCouponBatches', function ($q) use ($request) {
                $q->where('activity_id', $request->acid);
            });
        }

        if ($request->has('oid')) {
            //用户已激活点位
            $oids = UserActivation::query()->where('uid', $member->uid)->pluck('oid')->toArray();
            abort_if(!in_array($request->oid, $oids), 500, '无可用优惠券');

            //优惠券对用点位
            $query->whereHas('marketPointCouponBatches', function ($q) use($request) {
                $q->where('oid', $request->oid);
            });
        }

        if ($request->has('marketid')) {
            //用户已激活商场
            $marketids = UserActivation::query()->where('uid', $member->uid)->pluck('marketid')->toArray();
            abort_if(!in_array($request->marketid, $marketids), 500, '无可用优惠券');
        }

        //优惠券对应商场
        $query->whereHas('marketPointCouponBatches', function ($q) use($request, $member) {
            $marketId = $request->marketid ?: $member->marketid;
            $q->where('marketid', $marketId);
        });

        $per_page = $request->get('per_page') ? : 5;
        $couponBatches = $query->where('is_active', 1)->orderByDesc('sort_order')->paginate($per_page);

        abort_if($couponBatches->isEmpty(), 500, '无可用优惠券');

        //业务参数过滤
        foreach ($couponBatches as $key => $couponBatch) {

            if (!$couponBatch->pmg_status && !$couponBatch->dmg_status && $couponBatch->stock <= 0) {

                $couponBatches->forget($key);
            }
        }

        abort_if($couponBatches->isEmpty(), 500, '无可用优惠券');

        return $this->response->paginator($couponBatches, new CouponBatchTransformer());

    }

    public function couponBatchShow(CouponBatch $couponBatch)
    {
        return $this->response->item($couponBatch, new CouponBatchTransformer());
    }

    /**
     * 领取优惠券
     * @param CouponBatch $couponBatch
     * @param MiniCouponRequest $request
     * @return mixed
     */
    public function store(CouponBatch $couponBatch, MiniCouponRequest $request, Client $client)
    {
        Log::info('mini_coupon_store', $request->all());
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();
        $memberUID = $member->uid;

        abort_if(!$couponBatch->is_active, 500, '该券已被停用!');

        if ($couponBatch->is_fixed_date) {
            $now = Carbon::now()->toDateTimeString();
            abort_if($couponBatch->end_date < $now, 500, '该券已过期!');
        }

        if (!$couponBatch->dmg_status && !$couponBatch->pmg_status && $couponBatch->stock <= 0) {
            abort(500, '优惠券已发完!');
        }

        //扫码领取记录
        if ($request->has('qiniu_id')) {
            $coupon = Coupon::query()->where('qiniu_id', $request->get('qiniu_id'))->first();
            abort_if($coupon, 500, '该券已被领取!');
        }

        //每天最大领取量
        if (!$couponBatch->dmg_status) {
            $dateString = Carbon::now()->toDateString();
            $coupon = Coupon::query()->where('coupon_batch_id', $couponBatch->id)
                ->whereRaw("date_format(created_at,'%Y-%m-%d')='$dateString'")
                ->selectRaw("count(coupon_batch_id) as day_receive")->first();

            if ($coupon->day_receive >= $couponBatch->day_max_get) {
                abort(500, '该券今日已发完，明天再来领取吧！');
            }
        }

        //用户最大领取量
        if (!$couponBatch->pmg_status) {
            $coupons = Coupon::query()->where('member_uid', $memberUID)
                ->where('coupon_batch_id', $couponBatch->id)
                ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->get();

            if ($coupons->count() >= $couponBatch->people_max_get) {
                abort(500, '优惠券每人最多领取' . $couponBatch->people_max_get . '张');
            }
        }

        $traceCode = uniqid();

        DB::beginTransaction();

        try {

            //券的有效期
            if ($couponBatch->is_fixed_date) {
                $startDate = Carbon::createFromTimeString($couponBatch->start_date);;
                $endDate = Carbon::createFromTimeString($couponBatch->end_date);
            } else {
                $startDate = Carbon::now()->addDays($couponBatch->delay_effective_day);
                $endDate = Carbon::now()->addDays($couponBatch->delay_effective_day + $couponBatch->effective_day);
            }

            //创建优惠券
            $coupon = Coupon::create([
                'code' => uniqid(),
                'coupon_batch_id' => $couponBatch->id,
                'status' => 3,
                'oid' => $member->oid,
                'member_uid' => $memberUID,
                'qiniu_id' => $request->get('qiniu_id') ? : 0,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            //不使用系统核销 领取优惠券后 ，自动减去库存
            if (!$couponBatch->write_off_status && !$couponBatch->pmg_status && !$couponBatch->pmg_status) {
                $couponBatch->decrement('stock');
            }

            //积分兑换
            if ($couponBatch->credit) {
                //积分扣除接口
                $response = $client->request('GET', 'https://exelook.com/client//open/userhd/', [
                    'query' => [
                        'z' => $request->z,
                        'api' => 'json',
                        'num' => $couponBatch->credit,
                        'key' => $traceCode,
                    ],
                ]);

                $callback = json_decode($response->getBody()->getContents(), true);

                if ($callback['state'] != '1') {
                    throw new \Exception("兑换失败");
                }

                //积分记录
                XsCreditRecord::create([
                    'uid' => $memberUID,
                    'num' => $couponBatch->credit,
                    'key' => $traceCode,
                ]);

            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();//事务回滚
            abort(500, $e->getMessage());
        }

        return $this->response->item($coupon, new CouponTransformer());
    }

    /**
     * 小程序随机发放优惠券
     * @param CouponBatch $couponBatch
     * @param MiniCouponRequest $request
     * @param Client $client
     * @return \Dingo\Api\Http\Response
     */
    public function randomSending(CouponBatch $couponBatch, MiniCouponRequest $request, Client $client)
    {
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();
        $query = $couponBatch->query();

        //优惠券对应商场
        $query->whereHas('marketPointCouponBatches', function ($q) use($request, $member) {
            $marketId = $request->marketid ?: $member->marketid;
            $q->where('marketid', $marketId);
        });

        $couponBatches = $query->orderByDesc('sort_order')->get();

        abort_if($couponBatches->isEmpty(), 500, '无可用优惠券');

        //业务参数过滤
        foreach ($couponBatches as $key => $couponBatch) {

            if (!$couponBatch->pmg_status && !$couponBatch->dmg_status && $couponBatch->stock <= 0) {

                $couponBatches->forget($key);
            }
        }

        abort_if($couponBatches->isEmpty(), 500, '无可用优惠券');

        $couponBatch = $couponBatches->random();

        return $this->response->item($couponBatch, new CouponBatchTransformer());
    }


}
