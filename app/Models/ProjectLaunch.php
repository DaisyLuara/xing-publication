<?php

namespace App\Models;


class ProjectLaunch extends Model
{
    protected $connection = 'ar';
    public $table = 'istar_tv_oid';

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'default_plid', 'id');
    }
}
