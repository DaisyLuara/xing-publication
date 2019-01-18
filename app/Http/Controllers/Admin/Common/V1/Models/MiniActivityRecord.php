<?php

namespace App\Http\Controllers\Admin\Common\V1\Models;


use App\Models\Model;

class MiniActivityRecord extends Model
{
    protected $fillable = [
        'member_uid',
        'from',
        'to',
        'description',
    ];
}