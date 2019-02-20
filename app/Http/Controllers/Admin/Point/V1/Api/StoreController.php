<?php

namespace App\Http\Controllers\Admin\Point\V1\Api;

use App\Http\Controllers\Admin\Point\V1\Request\StoreRequest;
use App\Http\Controllers\Admin\Point\V1\Transformer\StoreTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use DB;

class StoreController extends Controller
{
    public function index(Request $request, Store $store)
    {
        $query = $store->query();

        //商户名称
        if ($request->has('store_name')) {
            $query->where('name', 'like', '%' . $request->store_name . '%');
        }

        //所属公司名称
        if ($request->has('company_name')) {
            $companyName = $request->company_name;
            $query->whereHas('company', function ($q) use ($companyName) {
                $q->where('name', 'like', '%' . $companyName . '%');
            });
        }

        //所属场地名称
        if ($request->has('marketid')) {
            $query->where('marketid', $request->marketid);
        }

        //商户属性
        if ($request->has('type')) {
            $query->where('type', '=', $request->type);
        }

        //区域
        if ($request->has('areaid')) {
            $query->where('areaid', '=', $request->areaid);
        }

        $stores = $query->paginate(10);
        return $this->response->paginator($stores, new StoreTransformer());
    }

    public function show(Store $store)
    {
        return $this->response->item($store, new StoreTransformer());
    }

    public function store(StoreRequest $request, Store $store)
    {
        DB::beginTransaction();

        try {

            $store->fill($request->all());
            $store->save();

            // 合约配置
            if ($request->filled('contract_id')) {
                $store->contract()->update($request->only(['start_date', 'end_date']));
            }

            //商户核销人员配置
            if ($request->has('customer') && count(array_filter($request->customer))) {
                $this->generateCustomer($request, $store);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            abort(500, $e->getMessage());
        }

        return $this->response->item($store, new StoreTransformer());
    }

    public function update(StoreRequest $request, Store $store)
    {
        $store->update($request->all());

        if ($request->filled('contract_id')) {
            $store->contract()->update($request->only(['start_date', 'end_date']));
        }

        //商户核销人员配置
        if ($request->has('customer') && count(array_filter($request->customer))) {
            $this->generateCustomer($request, $store);
        }

        return $this->response->item($store, new StoreTransformer());
    }

    private function generateCustomer($request, $store)
    {
        if ($request->customer['type'] == "add") {
            abort_if(!$request->customer['phone'] || !$request->customer['password'], 500, '核销人员信息不完整');

            $customer = $store->writeOffCustomer()->create([
                'name'     => $request->customer['name'],
                'company_id' => $request->company_id,
                'phone' => $request->customer['phone'],
                'password' => bcrypt($request->customer['password']),
                'position' => '商户核销人员',
            ]);

        } else {
            $customer = Customer::query()->where('phone', $request->customer['phone'])->first();
            abort_if(!$customer, 500, '未找到联系人,请检查手机号');
        }

        if (!$customer->hasRole('market_owner')) {
            $customer->assignRole('market_owner');
        }

        $store->update(['write_off_customer_id' => $customer->id]);
    }
}
