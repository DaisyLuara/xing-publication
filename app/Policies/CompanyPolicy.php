<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Company $company)
    {
        return $user->isAdmin() || $user->isAuthorOf($company);
    }

    public function update(User $user, Company $company)
    {
        return $user->isAuthorOf($company);
    }

    public function destroy(User $user, Company $company)
    {
        return $user->isAuthorOf($company);
    }
}