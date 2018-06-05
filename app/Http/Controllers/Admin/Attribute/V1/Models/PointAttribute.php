<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Models;

use App\Models\Model;

class PointAttribute extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_point_attributes';
    public $timestamps = false;
    protected $fillable = ['point_id', 'attribute_id'];
}
