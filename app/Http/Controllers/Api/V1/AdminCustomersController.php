<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\CustomerRequest;
use App\Transformers\CustomerTransformer;
use App\Models\Customer;
use App\Models\Company;

class AdminCustomersController extends Controller
{
    public function index(Company $company, Customer $customer)
    {
        $this->authorize('index', [$customer, $company]);

        $query = $customer->query();

        $currentUser = $this->user();

        if ($currentUser->isAdmin()) {
            $customers = $query->paginate(10);
        } else {
            $customers = $query->whereHas('company', function ($q) use ($company) {
                $q->where('id', $company->id);
            })->paginate(10);
        }

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

        $customer->fill($request->all());
        $customer->company_id = $company->id;
        $customer->save();

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
