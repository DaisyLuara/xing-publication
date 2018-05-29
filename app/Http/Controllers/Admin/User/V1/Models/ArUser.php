<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

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
