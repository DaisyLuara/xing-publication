<?php

namespace App\Observers;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ProjectLaunchTplObserver
{
    public function saving(ProjectLaunchTpl $tpl)
    {


        $tpl->cid = 1007;
        $tpl->pid = 4;
        $tpl->date = date('Y-m-d H:i:s');
        $tpl->clientdate = time() * 1000;

    }

    public function updating(ProjectLaunchTpl $tpl)
    {
        $tpl->clientdate = time() * 1000;
    }
}