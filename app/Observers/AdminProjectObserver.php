<?php

namespace App\Observers;

use App\Models\AdminProject;
use App\Models\User;
use App\Notifications\CheckProject;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class AdminProjectObserver
{
    public function created(AdminProject $adminProject)
    {
        //通知给所有的 法务进行审核
        User::query()->whereHas('roles', function ($q) {
            $q->where('name', '=', 'legal-affairs');
        })->get()->each(function ($user) use ($adminProject) {
            $user->notify(new CheckProject($adminProject));
        });
    }
}