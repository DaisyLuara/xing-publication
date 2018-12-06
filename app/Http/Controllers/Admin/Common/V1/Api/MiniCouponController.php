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
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Common\V1\Transformer\CouponTransformer;
use App\Http\Controllers\Admin\Common\V1\Request\MiniCouponRequest;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberSession;
use function GuzzleHttp\Psr7\parse_query;
use App\Http\Controllers\Controller;
use App\Handlers\ImageUploadHandler;
use Carbon\Carbon;
use Log;
use Illuminate\Http\Request;


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
            $couponQuery->where('status', $request->get('status'));
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
    public function couponBatchesIndex(Request $request, CouponBatch $couponBatch)
    {
        /**
         * @todo  多个商户参加活动 优惠券配置
         * 新增字段 campaign_id 硬编码 获取活动ID为1的优惠券
         */
        $query = $couponBatch->query();

        if ($request->has('scene')) {
            $scene = $request->scene;
            $scene = str_replace('istart:', '', $scene);
            $id = explode('_', $scene)[0];
            $fileUpload = FileUpload::query()->findOrFail($id);

            abort_if(!$fileUpload->acid, 500, '无效活动');
            $query->whereHas('activityCouponBatches', function ($q) use($fileUpload){
                $q->where('activity_id', $fileUpload->acid);
            });
        }

        $couponBatches = $query->orderByDesc('sort_order')->get();

        abort_if($couponBatches->isEmpty(), 500, '无可用优惠券');

        //业务参数过滤
        foreach ($couponBatches as $key => $couponBatch) {

            if (!$couponBatch->pmg_status && !$couponBatch->dmg_status && $couponBatch->stock <= 0) {

                $couponBatches->forget($key);
            }
        }

        abort_if($couponBatches->isEmpty(), 500, '无可用优惠券');

        return $this->response->collection($couponBatches, new CouponBatchTransformer());

    }

    /**
     * 领取优惠券
     * @param CouponBatch $couponBatch
     * @param MiniCouponRequest $request
     * @return mixed
     */
    public function store(CouponBatch $couponBatch, MiniCouponRequest $request)
    {
        Log::info('mini_coupon_store', $request->all());
        $member = ArMemberSession::query()->where('z', $request->z)->firstOrFail();
        $memberUID = $member->uid;

        if (!$couponBatch->dmg_status && !$couponBatch->pmg_status && $couponBatch->stock <= 0) {
            abort(500, '优惠券已发完!');
        }

        $now = Carbon::now()->timestamp;
        $startDate = strtotime($couponBatch->start_date);
        $endDdate = strtotime($couponBatch->end_date);

        abort_if($now <= $startDate, 500, '活动未开启!');
        abort_if($now >= $endDdate, 500, '活动已结束!');

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
                abort(500, '您今天已经领过了，请明天再来!');
            }
        }

        //创建优惠券
        $coupon = Coupon::create([
            'code' => uniqid(),
            'coupon_batch_id' => $couponBatch->id,
            'status' => 3,
            'member_uid' => $memberUID,
        ]);

        //减少库存
        if (!$couponBatch->pmg_status && !$couponBatch->pmg_status) {
            $couponBatch->decrement('stock');
        }

        return $this->response->item($coupon, new CouponTransformer());

    }

}
