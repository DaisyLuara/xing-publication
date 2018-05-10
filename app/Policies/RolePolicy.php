<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public function index(User $currentUser)
    {
        return $currentUser->hasRole('super_admin') || $currentUser->hasRole('admin');
    }

}