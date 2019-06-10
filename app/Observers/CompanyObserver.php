<?php

namespace App\Observers;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Models\User;
use App\Notifications\CheckCompany;
use Illuminate\Support\Facades\Notification;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class CompanyObserver
{
    public function created(Company $company)
    {
        $users = User::query()->role('legal-affairs')->get(); //法务主管
        Notification::send($users, new CheckCompany($company));
    }
}