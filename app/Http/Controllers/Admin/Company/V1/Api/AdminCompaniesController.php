<?php

namespace App\Http\Controllers\Admin\Company\V1\Api;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Company\V1\Request\CompanyRequest;
use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Jobs\CreateAdminStaffJob;

class AdminCompaniesController extends Controller
{
    public function index(Request $request, Company $company)
    {
        $query = $company->query();
        /** @var  $currentUser \App\Models\User */
        $currentUser = $this->user();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->has('internal_name')) {
            $query->where('internal_name', 'like', '%' . $request->get('internal_name') . '%');
        }

        if ($request->has('category')) {
            $query->where('category', '=', $request->get('category'));
        }

        if ($request->has('status')) {
            $query->where('status', '=', $request->get('status'));
        }

        if ($request->has('bd_name')) {
            $query->whereHas('bdUser', static function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('bd_name') . '%');
            });
        }

        //角色为管理员，法务，法务主管时，查看所有公司数据
        if ($currentUser->isAdmin() || $currentUser->hasRole('legal-affairs|legal-affairs-manager|operation')) {
            $companies = $query->orderByDesc('id')->paginate(10);
            return $this->response()->paginator($companies, new CompanyTransformer());

        }

        //角色为主管时，查看下属及自己
        if ($currentUser->parent_id === $currentUser->id) {
            $companies = $query->whereHas('user', function ($q) use ($currentUser) {
                $q->where('parent_id', $currentUser->id);
            })->orderByDesc('id')->paginate(10);
            return $this->response()->paginator($companies, new CompanyTransformer());
        }

        //查看自己数据
        $companies = $query->where('user_id', $currentUser->id)->orderByDesc('id')->paginate(10);
        return $this->response()->paginator($companies, new CompanyTransformer());
    }

    public function show(Company $company)
    {
//        $this->authorize('show', $company);

        return $this->response->item($company, new CompanyTransformer());
    }

    public function store(CompanyRequest $request, Company $company, Customer $customer)
    {
        $companyData = [
            'name' => $request->name,
            'internal_name' => $request->internal_name,
            'address' => $request->address,
            'category' => $request->category,
            'description' => $request->description,
            'logo_media_id' => $request->logo_media_id,
            'bd_user_id' => $request->bd_user_id,
            'parent_id' => $request->parent_id,
        ];
        $company->fill(array_merge($companyData, ['user_id' => $this->user()->id]));
        $company->save();
        activity('company')->on($company)->withProperties($request->all())->log('新增公司信息');

        if ($request->category == 0) {
            $customerData = [
                'name' => $request->customer_name,
                'position' => $request->position,
                'phone' => $request->phone,
                'telephone' => $request->telephone,
                'password' => bcrypt($request->password),
                'company_id' => $company->id,
            ];
            $customer = Customer::create($customerData);
            $role = Role::findById($request->role_id, 'shop');
            $customer->assignRole($role);
            CreateAdminStaffJob::dispatch($customer, $role)->onQueue('create_admin_staff');
        }
        activity('customer')->on($customer)->withProperties($companyData)->log('新增公司联系人');


        return $this->response->item($company, new CompanyTransformer())
            ->setStatusCode(201);

    }

    public function update(CompanyRequest $request, Company $company)
    {
//        $this->authorize('update', $company);

        $company->update($request->all());
        activity('company')->on($company)->withProperties($request->all())->log('修改公司信息');
        return $this->response->item($company, new CompanyTransformer());
    }

    public function export(Request $request)
    {
        return excelExportByType($request, 'company');
    }
}
