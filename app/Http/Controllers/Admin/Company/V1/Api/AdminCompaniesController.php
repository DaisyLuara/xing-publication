<?php

namespace App\Http\Controllers\Admin\Company\V1\Api;

use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Company\V1\Request\CompanyRequest;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;

class AdminCompaniesController extends Controller
{
    public function index(Request $request, Company $company)
    {
        $query = $company->query();
        /** @var  $currentUser \App\Models\User */
        $currentUser = $this->user();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($currentUser->isAdmin() || $currentUser->hasRole('legal-affairs') || $currentUser->hasRole('legal-affairs-manager')) {
            $companies = $query->orderByDesc('id')->paginate(10);
        } else {
            $companies = $query->whereHas('user', function ($q) use ($currentUser) {
                if ($currentUser->hasRole('user')) {
                    $q->where('id', $currentUser->id);
                } else {
                    $q->where('parent_id', $currentUser->id);
                }
            })->orderByDesc('id')->paginate(10);
        }

        return $this->response->paginator($companies, new CompanyTransformer());
    }

    public function show(Company $company)
    {
        $this->authorize('show', $company);

        return $this->response->item($company, new CompanyTransformer());
    }

    public function store(CompanyRequest $request, Company $company)
    {
        $companyData = [
            'name' => $request->name,
            'internal_name' => $request->internal_name,
            'address' => $request->address,
            'category' => $request->category,
            'description' => $request->description,
            'logo' => $request->logo
        ];
        $company->fill(array_merge($companyData, ['user_id' => $this->user()->id]));
        $company->save();

        $customerData = [
            'name' => $request->customer_name,
            'position' => $request->position,
            'phone' => $request->phone,
            'telephone' => $request->telephone,
            'password' => bcrypt($request->password),
            'company_id' => $company->id,
        ];
        /** @var  $customer \App\Models\Customer */
        $customer = Customer::create($customerData);
        $role = Role::findById($request->role_id, 'shop');
        $customer->assignRole($role);

        return $this->response->item($company, new CompanyTransformer())
            ->setStatusCode(201);

    }

    public function update(CompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        $company->update($request->all());
        return $this->response->item($company, new CompanyTransformer());
    }
}
