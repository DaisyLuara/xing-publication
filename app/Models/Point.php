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

<<<<<<< HEAD
    public function area()
    {
        return $this->hasOne(Area::class, 'areaid', 'areaid');
=======
    public function scene()
    {
        return $this->belongsTo(Scene::class, 'sid', 'sid');
>>>>>>> aa819bdd18dd85ea411ee984ace544037a07f27c
    }

    public function market()
    {
<<<<<<< HEAD
        return $this->hasOne(Market::class, 'marketid', 'marketid');
=======
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
>>>>>>> aa819bdd18dd85ea411ee984ace544037a07f27c
    }
}
