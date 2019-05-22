<?php

namespace App\Observers;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ProjectLaunchTplScheduleObserver
{
    public function saving(ProjectLaunchTplSchedule $schedule)
    {
        $schedule->cid = 1007;
        $schedule->pid = 4;
        $schedule->date = date('Y-m-d H:i:s');
        $schedule->clientdate = time() * 1000;

    }

    public function updating(ProjectLaunchTpl $schedule)
    {
        $schedule->clientdate = time() * 1000;
    }
}