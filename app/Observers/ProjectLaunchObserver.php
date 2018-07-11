<?php

namespace App\Observers;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch;
use Carbon\Carbon;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ProjectLaunchObserver
{
    public function saving(ProjectLaunch $projectLaunch)
    {

        if (!$projectLaunch->default_plid) {
            $projectLaunch->default_plid = 0;
        }

        if (!$projectLaunch->weekday_tvid) {
            $projectLaunch->weekday_tvid = 0;
        }

        if (!$projectLaunch->weekend_tvid) {
            $projectLaunch->weekend_tvid = 0;
        }

        if (!$projectLaunch->div_tvid) {
            $projectLaunch->div_tvid = 0;
        }

        if (!$projectLaunch->day1_tvid) {
            $projectLaunch->day1_tvid = 0;
        }

        if (!$projectLaunch->day2_tvid) {
            $projectLaunch->day2_tvid = 0;
        }

        if (!$projectLaunch->day3_tvid) {
            $projectLaunch->day3_tvid = 0;
        }

        if (!$projectLaunch->day4_tvid) {
            $projectLaunch->day4_tvid = 0;
        }

        if (!$projectLaunch->day5_tvid) {
            $projectLaunch->day5_tvid = 0;
        }

        if (!$projectLaunch->day6_tvid) {
            $projectLaunch->day6_tvid = 0;
        }

        if (!$projectLaunch->day7_tvid) {
            $projectLaunch->day7_tvid = 0;
        }

        if (!$projectLaunch->sdate) {
            $projectLaunch->sdate = Carbon::now()->startOfDay()->timestamp;
        }

        if (!$projectLaunch->edate) {
            $projectLaunch->edate = Carbon::now()->endOfDay()->timestamp;
        }

        $projectLaunch->cid = 1007;
        $projectLaunch->pid = 4;
        $projectLaunch->date = date('Y-m-d H:i:s');
        $projectLaunch->clientdate = time() * 1000;

    }

    public function updating(ProjectLaunch $projectLaunch)
    {
        $projectLaunch->clientdate = time() * 1000;
    }
}