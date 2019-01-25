<?php

namespace App\Http\Controllers\Admin\Company\V1\Api;

use App\Http\Controllers\Admin\Company\V1\Transformer\CustomerTransformer;
use App\Http\Controllers\Admin\Company\V1\Request\CustomerRequest;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use App\Models\Customer;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Controller;

class AdminCustomersController extends Controller
{
    public function index(Company $company, Customer $customer)
    {
//        $this->authorize('index', [$customer, $company]);

        $query = $customer->query();

        $customers = $query->whereHas('company', function ($q) use ($company) {
            $q->where('id', $company->id);
        })->orderByDesc('id')->paginate(10);

        return $this->response->paginator($customers, new CustomerTransformer());
    }

    public function show(Company $company, Customer $customer)
    {
//        $this->authorize('show', [$customer, $company]);

        return $this->response->item($customer, new CustomerTransformer());
    }

    public function store(CustomerRequest $request, Company $company, Customer $customer)
    {
//        $this->authorize('store', [$customer, $company]);

        /** @var  $customer \App\Models\Customer */
        $customer = Customer::create([
            'name' => $request->name,
            'position' => $request->position,
            'phone' => $request->phone,
            'telephone' => $request->telephone,
            'password' => bcrypt($request->password),
            'company_id' => $company->id,
        ]);
        $role = Role::findById($request->role_id, 'shop');
        $customer->assignRole($role);

        activity('customer')->on($customer)->withProperties($request->all())->log('新增公司联系人');

        return $this->response->item($customer, new CustomerTransformer())
            ->setStatusCode(201);

    }

    public function update(CustomerRequest $request, Company $company, Customer $customer)
    {
//        $this->authorize('update', [$customer, $company]);
        $input = $request->except('company_id');
        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        $customer->update($input);
        $role = Role::findById($request->role_id, 'shop');
        $customer->assignRole($role);

        activity('customer')->on($customer)->withProperties($request->all())->log('修改公司联系人');
        return $this->response->item($customer, new CustomerTransformer());
    }

}
