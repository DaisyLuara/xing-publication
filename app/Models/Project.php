<?php

namespace App\Models;

class Project extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_list';

    public function points()
    {
        return $this->belongsToMany(Point::class, 'istar_tv_oid', 'default_plid','oid');
    }
}
