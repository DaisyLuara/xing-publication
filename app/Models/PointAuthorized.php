<?php

namespace App\Models;

class PointAuthorized extends Model
{
    protected $connection = 'ar';
    public $table = 'admin_per_oid';

    public function point()
    {
        return $this->hasOne(Point::class, 'oid', 'oid');
    }

    public function ar_user()
    {
        return $this->hasOne(ArUser::class, 'uid', 'uid');
    }
}
