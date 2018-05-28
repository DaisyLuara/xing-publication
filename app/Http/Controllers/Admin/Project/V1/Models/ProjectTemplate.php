<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;

class ProjectTemplate extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_template';
    protected $primaryKey = 'tid';
    public $timestamps = false;
}
