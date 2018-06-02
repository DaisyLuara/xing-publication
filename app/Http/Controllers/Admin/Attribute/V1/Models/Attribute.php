<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Models;

use App\Models\Model;


class Attribute extends Model
{
    protected $fillable = ['name', 'pid', 'desc'];
}
