<?php

namespace App\Models;

class Point extends Model
{

    protected $connection = 'ar';
    public $table = 'avr_official';
    protected $primaryKey = 'oid';

    public function arUsers()
    {
        return $this->belongsToMany(ArUser::class, 'admin_per_oid', 'oid', 'uid');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'istar_tv_oid', 'oid', 'default_plid');
    }
}
