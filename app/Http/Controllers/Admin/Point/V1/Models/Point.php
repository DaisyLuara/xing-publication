<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\User\V1\Models\ArUser;
use App\Models\Model;

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

    public function scene()
    {
        return $this->belongsTo(Scene::class, 'sid', 'sid');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaid', 'areaid');
    }

}
