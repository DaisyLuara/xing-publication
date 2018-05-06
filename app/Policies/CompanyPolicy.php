<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    //只允许管理员或者创建者查看客户数据
    public function index(User $user, Company $company)
    {
        return $user->isAdmin() || $user->isAuthorOf($company);
    }

    public function show(User $user, Company $company)
    {
        return $user->isAdmin() || $user->isAuthorOf($company);
    }

    //只允许 普通用户 创建和修改 公司信息
    public function update(User $user, Company $company)
    {
        return $user->isAuthorOf($company);
    }

    public function destroy(User $user, Company $company)
    {
        return $user->isAuthorOf($company);
    }
}