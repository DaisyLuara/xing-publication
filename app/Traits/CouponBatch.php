<?php
/**
 * Created by IntelliJ IDEA.
 * User: chenzhong
 * Date: 2019-03-23
 * Time: 14:34
 */

namespace App\Traits;

use App\Http\Controllers\Admin\Coupon\V1\Models\UserCouponBatch;
use App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch as CouponBatchModel;
use App\Http\Controllers\Admin\Coupon\V1\Models\Coupon;
use App\Http\Controllers\Admin\MallCoo\V1\Models\GameResult;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberSession;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Models\WeChatUser;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use Carbon\Carbon;
use DB;
use Log;


trait CouponBatch
{
    public function generate($oid, $belong)
    {
        $policyID = $this->getPolicy($oid, $belong);
        $couponBatchPolicies = $this->getCouponBatchPolicies($policyID);


        /**
         * 移除不符合条件的优惠券规则
         */
        foreach ($couponBatchPolicies as $key => $couponBatchPolicy) {

            if (!$this->isLimitStock($couponBatchPolicy)) {
                continue;
            }

            if ($this->checkStock($couponBatchPolicy)) {
                unset($couponBatchPolicies[$key]);
                continue;
            }

            if ($this->checkDynamicStock($couponBatchPolicy)) {
                unset($couponBatchPolicies[$key]);
                continue;
            }

            if ($this->checkDailyStock($couponBatchPolicy)) {
                unset($couponBatchPolicies[$key]);
                continue;
            }
        }

        abort_if($this->checkRate($couponBatchPolicies), 500, '无可用优惠券');

        return $this->getCouponBatch($couponBatchPolicies);
    }

    public function getCouponBatch($couponBatchPolicies)
    {
        /** @var \App\Http\Controllers\Api\V1\Coupon\Models\CouponBatch $targetCouponBatch */
        $targetCouponBatch = getRand($couponBatchPolicies);

        return CouponBatchModel::findOrFail($targetCouponBatch->coupon_batch_id);

    }

    public function isLimitStock($couponBatchPolicy): bool
    {
        return !$couponBatchPolicy->pmg_status && !$couponBatchPolicy->dmg_status;
    }

    public function getCouponBatchPolicies($policyID): array
    {
        $query = DB::table('coupon_batch_policy');

        $couponBatchPolicies = $query->join('coupon_batches', 'coupon_batch_id', '=', 'coupon_batches.id')
            ->where('policy_id', '=', $policyID)
            ->where('coupon_batches.is_active', 1)
            ->get();

        abort_if($couponBatchPolicies->isEmpty(), 500, '无可用优惠券');

        return $couponBatchPolicies->toArray();
    }

    public function getPolicy($oid, $belong)
    {
        $policy = PolicyLaunch::query()->where('oid', $oid)
            ->where('belong', $belong)
            ->firstOrFail();
        return $policy->policy_id;
    }

    public function checkStock($couponBatchPolicy): bool
    {
        return $couponBatchPolicy->stock <= 0;
    }

    public function checkDynamicStock($couponBatchPolicy): bool
    {
        if ($couponBatchPolicy->dynamic_stock_status) {
            $count = Coupon::query()->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->whereIn('status', [0, 3])
                ->where('coupon_batch_id', $couponBatchPolicy->id)->count('id');
            $dynamicStock = $couponBatchPolicy->stock - $count;
            return $dynamicStock <= 0;
        }

        return false;
    }

    public function checkDailyStock($couponBatchPolicy): bool
    {
        $now = Carbon::now()->toDateString();
        $coupon = Coupon::query()->where('coupon_batch_id', $couponBatchPolicy->id)
            ->whereRaw("date_format(created_at,'%Y-%m-%d')='$now'")
            ->selectRaw('count(coupon_batch_id) as day_receive')->first();

        return $coupon->day_receive >= $couponBatchPolicy->day_max_get;
    }

    public function checkRate($couponBatchPolicies): bool
    {
        return collect($couponBatchPolicies)->sum('rate') === 0;
    }

    /**
     * 设置券码url
     * @param Coupon $coupon
     * @param string $prefix
     * @param null $wechatCouponBatch
     * @param string $code_type
     * @return mixed
     */
    public function setCodeImageUrl($coupon, $prefix, $wechatCouponBatch = null, $code_type = 'qrcode')
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

    /**
     * 发券券码短信(猫酷)
     * @param Coupon $coupon
     * @param int $wxUserId
     * @param int $marketid
     * @return
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    private function sendCouponMsg($coupon, $wxUserId, $marketid)
    {
        /** @var ThirdPartyUser $user */
        $user = ThirdPartyUser::query()->where('wx_user_id', $wxUserId)
            ->where('marketid', $marketid)->firstOrFail();

        $sql = 'SELECT t.rank FROM (SELECT u.id, u.score, @rank := @rank + 1, @last_rank := CASE WHEN @last_score = u.score THEN @last_rank WHEN @last_score := u.score THEN @rank END AS rank FROM (SELECT * FROM jingsaas.oc_game_attribute where belong = "h5_beat_pig" ORDER BY score DESC) u, (SELECT @rank := 0, @last_score := NULL, @last_rank := 0) r) t LEFT JOIN jingsaas.oc_game_result r on t.id = r.game_attribute_id WHERE r.user_id = ' . $wxUserId;
        $result = DB::connection('jingsaas')->select($sql)[0];

        $easySms = new EasySms(config('easysms'));
        $couponBatch = $coupon->couponBatch;
        $content = "【星视度】尊敬的吾悦广场用户，恭喜您获得常州武进吾悦周年庆福利一份，游戏排名：" . $result->rank . "，优惠券：" . $coupon->code . "，奖品：" . $couponBatch->name . "，请在2019年5月21号10点-5月31号22点期间前往武进吾悦2F客服台凭此短信领取。感谢您对吾悦广场的参与与支持！回T退订";

        try {
            $easySms->send($user->mobile, [
                'content' => $content,
            ], ['yunpian' => ['api_key' => env('YUNPIAN_MARKETING_API_KEY')]]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
            $response = $exception->getMessage();
            return $this->response->errorInternal($response ?? '短信发送异常');
        }
    }

    /**
     * 获取用户信息
     * @param Request $request
     * @return ArMemberSession|WeChatUser|WeChatUser[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getUser($request)
    {
        if ($request->has('z')) {
            $member = ArMemberSession::query()->where('z', $request->get('z'))->firstOrFail();
            return $member;
        }

        return WeChatUser::query()->findOrFail(decrypt($request->get('sign')));

    }

    /**
     *用户查询sql
     * @param Request $request
     * @param $user
     * @return string
     */
    public function getUserQuerySql($request, $user)
    {
        return $request->get('z') ? 'member_uid = ' . $user->uid : 'wx_user_id = ' . $user->id;
    }

    /**
     * 生成用户券规则
     * @param Request $request
     * @param \App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch $couponBatch
     * @param $user
     */
    public function generateUserCouponBatch($request, $couponBatch, $user)
    {
        if ($request->has('z')) {
            UserCouponBatch::query()->create([
                'member_uid' => $user->uid,
                'coupon_batch_id' => $couponBatch->id,
                'belong' => $request->get('belong'),
            ]);
            return;
        }

        UserCouponBatch::query()->create([
            'wx_user_id' => $user->id,
            'coupon_batch_id' => $couponBatch->id,
            'belong' => $request->get('belong'),
        ]);

    }
}