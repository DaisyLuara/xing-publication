<?php

namespace App\Models;


class PointProject extends Model
{
    //@todo 和另外一个表 定义重复了 ProjectLaunch
    protected $connection = 'ar';
    public $table = 'istar_tv_oid';
    protected $primaryKey = 'tvoid';
}
