<?php

namespace App\Http\Controllers\Admin\Common\V1\Models;

use App\Models\Model;

class TempCustomer extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'address',
        'age',
        'gender',
        'oid',
        'belong',
    ];

}
