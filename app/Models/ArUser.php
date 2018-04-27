<?php

namespace App\Models;

class ArUser extends Model
{
    protected $connection = 'ar';
    public $table = 'admin_staff';
    protected $primaryKey = 'uid';
}
