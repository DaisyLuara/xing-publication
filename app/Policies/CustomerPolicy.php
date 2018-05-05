<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Customer $customer)
    {
        return $user->isAdmin() || $user->isAuthorOf($customer);
    }

    public function update(User $user, Customer $customer)
    {
        return $user->isAuthorOf($customer);
    }

    public function destroy(User $user, Customer $customer)
    {
        return $user->isAuthorOf($customer);
    }
}