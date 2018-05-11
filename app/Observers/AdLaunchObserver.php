<?php

namespace App\Observers;

use App\Models\AdLaunch;
use Carbon\Carbon;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class AdLaunchObserver
{
    public function saving(AdLaunch $adLaunch)
    {
        if (!$adLaunch->sdate) {
            $adLaunch->sdate = Carbon::now()->startOfDay()->timestamp;
        }

        if (!$adLaunch->edate) {
            $adLaunch->edate = Carbon::now()->endOfDay()->timestamp;
        }

        $adLaunch->date = date('Y-m-d H:i:s');
        $adLaunch->clientdate = time() * 1000;

    }

    public function updating(ProjectLaunch $adLaunch)
    {
        $adLaunch->clientdate = time() * 1000;
    }
}