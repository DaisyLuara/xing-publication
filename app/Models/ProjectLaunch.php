<?php

namespace App\Models;


use Spatie\Activitylog\Traits\LogsActivity;

class ProjectLaunch extends Model
{
    use LogsActivity;

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
}
