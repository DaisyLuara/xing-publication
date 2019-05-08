<?php

namespace App\Http\Controllers\Admin\Coupon\V1\Api;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Coupon\V1\Request\ImportCouponRequest;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Point\V1\Models\MarketConfig;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Http\Controllers\Controller;
use App\Imports\CouponBatchImport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportCouponController extends Controller
{
    public function importCouponBatchAndPolicy(Company $company, ImportCouponRequest $request): Response
    {

        /** @var User $user */
        $user = Auth::user();
        /** @var Policy $policy */
        $policy = Policy::query()->findOrFail($request->get('policy_id'));
        if ($policy->company_id !== $company->id) {
            abort(500, '策略的公司与当前所选公司不一致');
        }
        /** @var Media $media */
        $media = Media::query()->findOrFail($request->get('media_id'));
        $filename = urldecode(array_last(explode('/', $media->url)));
        $array = \Maatwebsite\Excel\Facades\Excel::toArray(new CouponBatchImport, $filename, 'qiniu');
        $excel_params = [];
        if ($array && $array[0]) {
            foreach ($array[0] as $key => $item) {
                if ($key === 0) {
                    continue;
                }

                //各项值的判断
                if (!$item[0]) {
                    abort(500, '第' . ($key + 1) . '行: 第A列请输入正确的优惠券名称');
                }
                if (!$item[1]) {
                    abort(500, '第' . ($key + 1) . '行: 第B列请输入正确的使用条款/规则');
                }
                if ((int)$item[2] <= 0) {
                    abort(500, '第' . ($key + 1) . '行: 第C列请输入正确的库存总量');
                }
                if ((int)$item[3] <= 0) {
                    abort(500, '第' . ($key + 1) . '行: 第D列请输入正确的剩余库存');
                }
                if (!in_array($item[4], ['关闭', '开启'])) {
                    abort(500, '第' . ($key + 1) . '行: 第E列请输入正确的是否每人无限领取【关闭/开启】');
                }
                if (($item[4] === '关闭' && (int)$item[5] <= 0) || ($item[4] === '开启' && (int)$item[5] !== 0)) {
                    abort(500, '第' . ($key + 1) . '行: 第F列请输入正确的每人最大获取数(开启无限领取时，填写0)');
                }
                if (!in_array($item[6], ['关闭', '开启'])) {
                    abort(500, '第' . ($key + 1) . '行: 第G列请输入正确的是否每天无限领取【关闭/开启】');
                }
                if (($item[6] === '关闭' && (int)$item[7] <= 0) || ($item[6] === '开启' && (int)$item[7] !== 0)) {
                    abort(500, '第' . ($key + 1) . '行: 第H列请输入正确的每天最大获取数(开启无限领取时，填写0)');
                }
                if (!is_numeric($item[8]) || $item[8] <= 25569 ) {
                    abort(500, '第' . ($key + 1) . '行: 第I列请输入正确的开始日期');
                }
                if (!is_numeric($item[9]) ||  $item[9] <= 25569) {
                    abort(500, '第' . ($key + 1) . '行: 第J列请输入正确的结束日期');
                }
                if ($item[9] <= $item[8]) {
                    abort(500, '第' . ($key + 1) . '行: 结束日期请大于开始日期');
                }
                $start_date = Carbon::createFromTimestamp(($item[8] - 25569) * 86400, 'UTC')->toDateTimeString();
                $end_date = Carbon::createFromTimestamp(($item[9] - 25569) * 86400, 'UTC')->toDateTimeString();

                if (!is_numeric($item[10]) ||  (double)$item[10] < 0) {
                    abort(500, '第' . ($key + 1) . '行: 第K列请输入正确的概率');
                }
                if (!is_string($item[11]) || !($url = parse_url($item[11], PHP_URL_HOST)) || count(dns_get_record($url, DNS_A | DNS_AAAA)) <= 0 ) {
                    abort(500, '第' . ($key + 1) . '行: 第L列请输入正确的URL');
                }

                $excel_params[] = [
                    'name' => $item[0],
                    'description' => $item[1],
                    'count' => (int)$item[2],//库存总数
                    'stock' => (int)$item[3],//剩余库存
                    'pmg_status' => $item[4] === '开启' ? 1 : 0,//是否开启每天无限领取 1:开启,0:关闭
                    'people_max_get' => (int)$item[5],//每人最大获取数
                    'dmg_status' => $item[6] === '开启' ? 1 : 0,//是否开启每天无限领取 1:开启,0:关闭
                    'day_max_get' => (int)$item[7],//每天最大获取数
                    'start_date' => $start_date,//开始日期
                    'end_date' => $end_date,//结束日期
                    'rate' => (double)$item[10], // 概率
                    'image_url' => $item[11],//h5图片链接
                ];
            }
        }
        $default_param = [
            'company_id' => $company->id,
            'bd_user_id' => $company->user_id,//关联BD
            'scene_type' => $request->get('scene_type'),//场景类型 - 1:商场通用,2:商场自营,3:商户通用,4:商户自营
            'write_off_mid' => $request->get('write_off_mid'),//核销商场
            'write_off_sid' => $request->get('write_off_sid'),//核销商户
            'create_user_id' => $user->id,
            'bs_image_url' => null,//大屏图片链接
            'third_code' => null,//第三方优惠券特征码
            'amount' => 0,//金额
            'is_fixed_date' => 1,//是否固定日期,1:固定,0:不固定
            'delay_effective_day' => 0,//延后生效天数
            'effective_day' => 0,//有效天数
            'is_active' => 1,//1 启用,0 停用
            'type' => 1,
            'redirect_url' => '',//跳转链接
            'title' => '',//标题
            'campaign_id' => 0,//活动ID
            'credit' => null,//兑换积分
            'sort_order' => 1,//优先级
            'dynamic_stock_status' => 0,//是否计算 动态库存 0:否 1: 是
            'write_off_status' => 0,//是否是系统核销  0:否 1: 是
            'wechat_coupon_batch_id' => 0,//微信卡券ID
        ];
        DB::beginTransaction();
        try {
            foreach ($excel_params as $excel_param) {
                $rate = $excel_param['rate'];
                unset($excel_param['rate']);
                /** @var CouponBatch $couponBatch */
                $couponBatch = CouponBatch::query()->create(array_merge($excel_param, $default_param));
                //绑定投放商场(嗨抖)
                if ($request->filled('marketid') && !$request->get('oid')) {
                    $couponBatch->marketPointCouponBatches()->create([
                        'marketid' => $request->get('marketid'),
                    ]);
                }
                //绑定投放点位(嗨抖)
                if ($request->get('oid')) {
                    foreach ($request->get('oid') as $oid) {
                        $couponBatch->marketPointCouponBatches()->create([
                            'marketid' => $request->get('marketid'),
                            'oid' => $oid,
                        ]);
                    }
                }
                //绑定商场核销人员
                if ($request->filled('write_off_mid')) {
                    /** @var MarketConfig $marketConfig */
                    $marketConfig = MarketConfig::query()->findOrFail($request->get('write_off_mid'));
                    $marketConfig->company->customers->each(static function ($item) use ($couponBatch) {
                        $couponBatch->writeOffCustomers()->attach($item);
                    });
                }
                //绑定商户核销人员
                if ($request->get('write_off_sid')) {
                    foreach ($request->get('write_off_sid') as $store_id) {
                        /** @var Store $store */
                        $store = Store::query()->findOrFail($store_id);
                        $store->company->customers->each(static function ($item) use ($couponBatch) {
                            $couponBatch->writeOffCustomers()->attach($item);
                        });
                    }
                }
                abort_if((bool)$policy->batches()->find($couponBatch->id), 500, '已存在该奖品,请勿重复添加');
                $policy_params = [
                    'min_age' => 0,
                    'max_age' => 0,
                    'gender' => 0,
                    'rate' => $rate,
                    'type' => 'mix',
                    'min_score' => 0,
                    'max_score' => 0
                ];
                if ($policy_params['min_age'] && $policy_params['max_age']) {
                    $policy_params['type'] = 'age';
                } else if ($policy_params['rate'] > 0) {
                    $policy_params['type'] = 'rate';
                } else if ($policy_params['gender']) {
                    $policy_params['type'] = 'gender';
                }
                $policy->batches()->save($couponBatch, $policy_params);
                activity('coupon_batch')->on($couponBatch)->withProperties($request->all())->log('批量新增优惠券规则');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage() . '?');
        }
        return $this->response()->noContent();
    }
}