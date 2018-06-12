<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;

class ProjectLaunchTpl extends Model
{
    protected $connection = 'ar';
    public $table = 'istar_tv';
    protected $primaryKey = 'tvid';
    public $timestamps = false;

    public $fillable = [
        'cid',
        'pid',
        'oid',
        'name',
        'date',
        'clientdate',
    ];

    public function schedules()
    {
        return $this->hasMany(ProjectLaunchTplSchedule::class, 'tvid', 'tvid');
    }
}

