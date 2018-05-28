<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;


class PointProject extends Model
{
    //@todo 和另外一个表 定义重复了 ProjectLaunch
    protected $connection = 'ar';
    public $table = 'istar_tv_oid';
    protected $primaryKey = 'tvoid';
}
