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

    public function divtemplate()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'div_tvid', 'tvid');
    }

    public function day1template()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'day1_tvid', 'tvid');
    }
    public function day2template()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'day2_tvid', 'tvid');
    }
    public function day3template()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'day3_tvid', 'tvid');
    }
    public function day4template()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'day4_tvid', 'tvid');
    }
    public function day5template()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'day5_tvid', 'tvid');
    }
    public function day6template()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'day6_tvid', 'tvid');
    }
    public function day7template()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'day7_tvid', 'tvid');
    }
    public function weekdaytemplate()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'weekday_tvid', 'tvid');

    }
    public function weekendemplate()
    {
        return $this->belongsTo(ProjectLaunchTpl::class, 'weekend_tvid', 'tvid');

    }


}
