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

    public function area()
    {
        return $this->hasOne(Area::class, 'areaid', 'areaid');
    }

    public function market()
    {
        return $this->hasOne(Market::class, 'marketid', 'marketid');
    }
}
