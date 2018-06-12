<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

class ProjectLaunch extends Model
{
    protected $connection = 'ar';
    public $table = 'istar_tv_oid';
    protected $primaryKey = 'tvoid';
    public $timestamps = false;

    public $fillable = [
        'cid',
        'pid',
        'oid',
        'default_plid',
        'weekday_tvid',
        'weekend_tvid',
        'day1_tvid',
        'day2_tvid',
        'day3_tvid',
        'day4_tvid',
        'day5_tvid',
        'day6_tvid',
        'day7_tvid',
        'sdate',
        'edate',
        'div_tvid',
        'date'
    ];

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }


    public function project()
    {
        return $this->belongsTo(Project::class, 'default_plid', 'id');
    }

    public function template()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'div_tvid', 'tvid');
    }

}
