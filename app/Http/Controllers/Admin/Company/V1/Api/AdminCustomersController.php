<?php

namespace App\Http\Controllers\Admin\Company\V1\Api;

use App\Http\Controllers\Admin\Company\V1\Transformer\CustomerTransformer;
use App\Http\Controllers\Admin\Company\V1\Request\CustomerRequest;
use App\Models\Customer;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Controller;

class AdminCustomersController extends Controller
{
    public function index(Company $company, Customer $customer)
    {
        $this->authorize('index', [$customer, $company]);

        $query = $customer->query();

        $customers = $query->whereHas('company', function ($q) use ($company) {
            $q->where('id', $company->id);
        })->orderByDesc('id')->paginate(10);

        return $this->response->paginator($customers, new CustomerTransformer());
    }

    public function show(Company $company, Customer $customer)
    {
        $this->authorize('show', [$customer, $company]);

        return $this->response->item($customer, new CustomerTransformer());
    }

    public function store(CustomerRequest $request, Company $company, Customer $customer)
    {
        $this->authorize('store', [$customer, $company]);

        /** @var Customer $user */
        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'company_id' => $company->id,
        ]);


        return $this->response->item($customer, new CustomerTransformer())
            ->setStatusCode(201);

    }

    public function update(CustomerRequest $request, Company $company, Customer $customer)
    {
        $this->authorize('update', [$customer, $company]);

        $customer->update($request->except('company_id'));
        return $this->response->item($customer, new CustomerTransformer());
    }

}
