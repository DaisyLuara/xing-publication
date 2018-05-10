<?php

namespace App\Models\Mock;

use App\Models\Model;

class ProjectLaunchLocal extends Model
{
    public $table = 'project_launches';
    public $timestamps = false;
    protected $primaryKey = 'tvoid';

    public $fillable = [
        'cid',
        'pid',
        'oid',
        'default_plid',
        'weekday_tvid',
        'weekend_tvid',
        'sdate',
        'edate',
        'div_tvid',
        'date'
    ];
}
