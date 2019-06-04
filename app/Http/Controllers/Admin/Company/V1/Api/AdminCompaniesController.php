<?php

namespace App\Http\Controllers\Admin\Company\V1\Api;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Company\V1\Request\CompanyRequest;
use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use App\Http\Controllers\Admin\Resource\V1\Models\CompanyMediaGroup;
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
        return $this->response()->item($company, new CompanyTransformer());
    }

    public function store(CompanyRequest $request, Company $company, Customer $customer)
    {
        $user = $this->user();
        $companyData = [
            'name' => $request->get('name'),
            'internal_name' => $request->get('internal_name'),
            'address' => $request->get('address'),
            'category' => $request->get('category'),
            'description' => $request->get('description'),
            'logo_media_id' => $request->get('logo_media_id'),
            'bd_user_id' => $request->get('bd_user_id'),
            'parent_id' => $request->get('parent_id'),
        ];
        $company->fill(array_merge($companyData, ['user_id' => $this->user()->id]))->save();
        CompanyMediaGroup::create(['company_id' => $company->id, 'name' => '默认分组', 'type' => 'image']);
        CompanyMediaGroup::create(['company_id' => $company->id, 'name' => '默认分组', 'type' => 'video']);

        activity('create_company')
            ->causedBy($user)
            ->performedOn($company)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $companyData])
            ->log('新增公司信息');

        if ($request->get('category') === 0) {
            $customerData = [
                'name' => $request->get('customer_name'),
                'position' => $request->get('position'),
                'phone' => $request->get('phone'),
                'telephone' => $request->get('telephone'),
                'password' => bcrypt($request->get('password')),
                'company_id' => $company->id,
            ];
            $customer = Customer::create($customerData);
            $role = Role::findById($request->get('role_id'), 'shop');
            $customer->assignRole($role);
            CreateAdminStaffJob::dispatch($customer, $role)->onQueue('create_admin_staff');

            activity('create_customer')
                ->causedBy($user)
                ->performedOn($customer)
                ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $customerData])
                ->log('新增公司联系人');

        }


        return $this->response()->item($company, new CompanyTransformer())
            ->setStatusCode(201);

    }

    public function update(CompanyRequest $request, Company $company): \Dingo\Api\Http\Response
    {
        $company->update($request->all());
        activity('create_company')
            ->causedBy($this->user())
            ->performedOn($company)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('修改公司信息');

        return $this->response()->item($company, new CompanyTransformer());
    }

    public function export(Request $request)
    {
        return excelExportByType($request, 'company');
    }
}
