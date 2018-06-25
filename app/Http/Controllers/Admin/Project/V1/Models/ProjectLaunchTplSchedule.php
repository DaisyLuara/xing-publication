<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;


class ProjectLaunchTplSchedule extends Model
{
    protected $connection = 'ar';
    public $table = 'istar_tv_info';
    protected $primaryKey = 'tviid';
    public $timestamps = false;

    public $fillable = [
        'cid',
        'pid',
        'tvid',
        'plid',
        'shm',
        'ehm',
        'sdate',
        'edate',
        'div_tvid',
        'date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'plid', 'id');
    }
}
