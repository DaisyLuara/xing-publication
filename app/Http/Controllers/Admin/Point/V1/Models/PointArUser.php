<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;

class PointArUser extends Model
{
    protected $connection = 'ar';
    public $table = 'admin_per_oid';
}
