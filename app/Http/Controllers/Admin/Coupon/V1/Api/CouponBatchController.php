<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Api;


use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\WechatCouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Request\CouponBatchRequest;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\MarketConfig;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;

class CouponBatchController extends Controller
{
    public function show(CouponBatch $couponBatch)
    {
        return $this->response->item($couponBatch, new CouponBatchTransformer());
    }

    public function index(CouponBatch $couponBatch, Request $request)
    {
        $query = $couponBatch->query();
        $loginUser = $this->user;

        if ($loginUser->hasRole('user')) {
            $query->where('bd_user_id', '=', $loginUser->id);
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('create_user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->create_user_name . '%');
            });
        }

        if ($request->has('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        if ($request->has('scene_type')) {
            $query->where('scene_type', $request->get('scene_type'));
        }

        if ($request->has('status')) {
            $now = Carbon::now()->toDateTimeString();

            switch ($request->get('status')) {
                case 1:
                    $query->where(function ($query) use($now) {
                        $query->where(function ($q) use($now) {
                            $q->where('is_fixed_date', 1)->where('end_date', '>', $now)->where('start_date', '<', $now);
                        })->orWhere(function ($q) {
                            $q->where('is_fixed_date', 0)->where('is_active', 1);
                        });
                    });
                    break;
                case 2:
                    $query->where('is_fixed_date', 1)->where('start_date', '>', $now);
                    break;
                case 3:
                    $query->where(function ($query) use($now) {
                        $query->where(function ($q) use ($now) {
                            $q->where('is_fixed_date', 1)->where('end_date', '<', $now);
                        })->orWhere(function ($q) {
                            $q->where('is_fixed_date', 0)->where('is_active', 0);
                        });
                    });
                    break;
                default:
                    return null;
            }
        }

        $couponBatch = $query->orderByDesc('id')->paginate(10);
        return $this->response->paginator($couponBatch, new CouponBatchTransformer());
    }

    public function store(Company $company, CouponBatch $couponBatch, CouponBatchRequest $request)
    {
        //核销账户列表
        $customers = $this->getWriteOffCustomers($request);
//        dd($customers);

        $couponBatch->fill(array_merge([
            'company_id' => $company->id,
            'create_user_id' => $this->user->id,
            'bd_user_id' => $company->user_id,
        ], $request->except(['marketid', 'oid'])))->save();

        //绑定商场
        if ($request->filled('marketid') && empty($request->oid)) {
           $couponBatch->marketPointCouponBatches()->create([
               'marketid' => $request->marketid,
           ]);
        }

        //绑定点位
        if ($request->oid) {
            foreach ($request->oid as $oid) {
                $couponBatch->marketPointCouponBatches()->create([
                    'marketid' => $request->marketid,
                    'oid' => $oid,
                ]);
            }
        }

        if ($request->has('wechat')) {
            $wechatCouponBatch = WechatCouponBatch::query()->create($request->get('wechat'));
            $couponBatch->update(['wechat_coupon_batch_id' => $wechatCouponBatch->id]);
        }

        //绑定核销人员
        if ($customers) {
            $couponBatch->writeOffCustomers()->attach($customers);
        }

        activity('coupon_batch')->on($couponBatch)->withProperties($request->all())->log('新增优惠券规则');

        return $this->response->item($couponBatch, new CouponBatchTransformer())
            ->setStatusCode(201);
    }

    public function update(CouponBatch $couponBatch, Request $request)
    {
        //检查核销配置
//        $customers = $this->checkWriteOffCustomer($request);

        $couponBatch->update($request->except(['marketid', 'oid']));
        if ($request->wechat && $couponBatch->wechat) {
            $couponBatch->wechat()->update($request['wechat']);
        }

        //商场点位重新绑定
        $couponBatch->marketPointCouponBatches()->delete();
        if ($request->filled('marketid') && empty($request->oid)) {
            $couponBatch->marketPointCouponBatches()->create([
                'marketid' => $request->marketid,
            ]);
        }

        if ($request->oid) {
            foreach ($request->oid as $oid) {
                $couponBatch->marketPointCouponBatches()->create([
                    'marketid' => $request->marketid,
                    'oid' => $oid,
                ]);
            }
        }

        //重新绑定核销人员
        $couponBatch->writeOffCustomers()->detach();
        if ($customers) {
           $couponBatch->writeOffCustomers()->attach($customers);
        }

        activity('coupon_batch')->on($couponBatch)->withProperties($request->all())->log('修改优惠券规则');

        return $this->response->item($couponBatch, new CouponBatchTransformer())
            ->setStatusCode(201);
    }

    public function syncMallCooCouponBatch(Request $request)
    {
        $company = Company::findOrFail($request->company_id);

        $mall_coo = app('mall_coo');
        $sUrl = 'https://openapi10.mallcoo.cn/Coupon/PutIn/v1/GetAll/';
        $result = $mall_coo->send($sUrl);
        if ($result['Code'] == 1) {
            foreach ($result['Data'] as $data) {
                CouponBatch::query()->updateOrCreate(['third_code' => $data['PICMID']], [
                    'name' => $data['CouponName'],
                    'description' => $data['CouponDesc'],
                    'company_id' => $company->id,
                    'create_user_id' => $this->user->id,
                    'bd_user_id' => $company->user_id,
                    'count' => $data['StoreCount'],
                    'stock' => $data['StoreOverGount'],
                ]);
            }
        }

    }

    private function getWriteOffCustomers($request)
    {
        $customers = collect();

        if ($request->filled('write_off_mid')) {
            $marketConfig = MarketConfig::query()->findOrFail($request->write_off_mid);
            $marketCustomers = $marketConfig->company->customers;
//            abort_if(!$market->write_off_customer_id, 500, '该场地未指定核销人员');
//
//            $customers[] = $market->write_off_customer_id;
        }

        $storeCustomers = collect();
        //商户核销人员列表
        if (!empty($request->write_off_sid)) {
            foreach ($request->write_off_sid as $store_id) {
                $store = Store::query()->findOrFail($store_id);
                $storeCustomers->merge($store->company->customers);
            }
        }

        return $customers;
    }
}