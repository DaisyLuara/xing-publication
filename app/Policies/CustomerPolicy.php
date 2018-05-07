<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    //只允许管理员 和 作者查看
    public function index(User $user, Customer $customer, Company $company)
    {
        return $user->isAdmin() || $user->isAuthorOf($company);
    }

    public function show(User $user, Customer $customer, Company $company)
    {
        return $user->isAdmin() || ($user->isAuthorOf($company) && $company->isCompanyCustomer($customer));
    }

    //只允许 公司 创建者 创建 修改 删除 客户信息
    public function store(User $user, Customer $customer, Company $company)
    {
        return $user->isAuthorOf($company);
    }

    public function update(User $user, Customer $customer, Company $company)
    {
        return $user->isAuthorOf($company) && $company->isCompanyCustomer($customer);
    }

    public function destroy(User $user, Customer $customer, Company $company)
    {
        return $user->isAuthorOf($company) && $company->isCompanyCustomer($customer);
    }
}