<?php

namespace App\Http\Controllers\Admin\Company\V1\Api;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Company\V1\Request\CustomerRequest;
use App\Http\Controllers\Admin\Company\V1\Transformer\CustomerTransformer;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Jobs\CreateAdminStaffJob;
use Dingo\Api\Http\Response;

class AdminCustomersController extends Controller
{
    /**
     * @param Company $company
     * @param Customer $customer
     * @return \Dingo\Api\Http\Response
     */
    public function index(Company $company, Customer $customer): Response
    {
        $query = $customer->query();

        $customers = $query->whereHas('company', static function ($q) use ($company) {
            $q->where('id', $company->id);
        })->orderByDesc('id')->paginate(10);

        return $this->response()->paginator($customers, new CustomerTransformer());
    }

    public function show(Company $company, Customer $customer)
    {
        return $this->response()->item($customer, new CustomerTransformer())->setStatusCode(200);
    }

    public function store(CustomerRequest $request, Company $company)
    {
        /** @var  $customer \App\Models\Customer */
        $customer = Customer::create([
            'name' => $request->get('name'),
            'position' => $request->get('position'),
            'phone' => $request->get('phone'),
            'telephone' => $request->get('telephone'),
            'password' => bcrypt($request->get('password')),
            'company_id' => $company->id,
        ]);
        $role = Role::findById($request->get('role_id'), 'shop');
        $customer->assignRole($role);

        activity('customer')->on($customer)->withProperties($request->all())->log('新增公司联系人');

        CreateAdminStaffJob::dispatch($customer, $role)->onQueue('create_admin_staff');

        return $this->response()->item($customer, new CustomerTransformer())->setStatusCode(201);
    }

    public function update(CustomerRequest $request, Company $company, Customer $customer)
    {
        $input = $request->except('company_id');
        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        $customer->update($input);
        $role = Role::findById($request->get('role_id'), 'shop');
        $customer->syncRoles($role);

        activity('customer')->on($customer)->withProperties($request->all())->log('修改公司联系人');
        return $this->response()->item($customer, new CustomerTransformer())->setStatusCode(200);
    }

}
