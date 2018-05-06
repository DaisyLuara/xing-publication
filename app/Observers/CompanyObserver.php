<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\User;
use App\Notifications\CheckCompany;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class CompanyObserver
{
    public function created(Company $company)
    {
        //通知给所有的 法务进行审核
        User::query()->whereHas('roles', function ($q) {
            $q->where('name', '=', 'legal-affairs');
        })->get()->each(function ($user) use ($company) {
            $user->notify(new CheckCompany($company));
        });
    }
}