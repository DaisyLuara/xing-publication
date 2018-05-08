<?php

namespace App\Models;


class ProjectLaunchLocal extends Model
{
    public $table = 'project_launches';
    public $timestamps = false;

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
