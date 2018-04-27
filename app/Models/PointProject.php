<?php

namespace App\Models;


class PointProject extends Model
{
    protected $connection = 'ar';
    public $table = 'istar_tv_oid';
    protected $primaryKey = 'tvoid';
}
