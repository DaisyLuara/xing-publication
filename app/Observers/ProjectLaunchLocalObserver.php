<?php

namespace App\Observers;

use App\Models\Mock\ProjectLaunchLocal;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ProjectLaunchLocalObserver
{
    public function saving(ProjectLaunchLocal $projectLaunchLocal)
    {

        if (!$projectLaunchLocal->default_plid) {
            $projectLaunchLocal->default_plid = 0;
        }

        if (!$projectLaunchLocal->weekday_tvid) {
            $projectLaunchLocal->weekday_tvid = 0;
        }

        if (!$projectLaunchLocal->weekend_tvid) {
            $projectLaunchLocal->weekend_tvid = 0;
        }

        if (!$projectLaunchLocal->div_tvid) {
            $projectLaunchLocal->div_tvid = 0;
        }

        $projectLaunchLocal->cid = 1007;
        $projectLaunchLocal->pid = 4;
        $projectLaunchLocal->date = date('Y-m-d H:i:s');
        $projectLaunchLocal->clientdate = time() * 1000;

    }
}