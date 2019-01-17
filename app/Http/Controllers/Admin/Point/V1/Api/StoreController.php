<?php

namespace App\Http\Controllers\Admin\Point\V1\Api;

use App\Http\Controllers\Admin\Point\V1\Request\StoreRequest;
use App\Http\Controllers\Admin\Point\V1\Transformer\StoreTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $store->fill($request->all());
        $store->save();

        if ($request->filled('contract_id')) {
            $store->contract()->update($request->only(['start_date', 'end_date']));
        }

        if ($request->has('customer')) {
            $store->writeOffCustomer()->create([
                'name'     => $request->customer['name'],
                'company_id' => $request->company_id,
                'phone' => $request->customer['phone'],
                'password' => bcrypt($request->customer['password']),
                'position' => '商户核销人员',
            ]);
        }

        return $this->response->item($store, new StoreTransformer());
    }

    public function update(StoreRequest $request, Store $store)
    {
        $store->update($request->all());

        if ($request->filled('contract_id')) {
            $store->contract()->update($request->only(['start_date', 'end_date']));
        }

        return $this->response->item($store, new StoreTransformer());
    }
}
