<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Models;

use App\Models\Model;

class ProjectAttribute extends Model
{
    protected $connection = 'ar';
    public $table = 'xs_project_attributes';
    public $timestamps = false;
    protected $fillable = ['project_id', 'attribute_id'];

}
