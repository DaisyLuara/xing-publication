<?php

namespace App\Models;

class ArUser extends Model
{
    protected $connection = 'ar';
    public $table = 'admin_staff';
    protected $primaryKey = 'uid';

    public function points()
    {
        return $this->belongsToMany(Point::class, 'admin_per_oid', 'uid', 'oid');
    }
}
