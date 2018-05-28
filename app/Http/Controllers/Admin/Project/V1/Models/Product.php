<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;

class Product extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product';
    protected $primaryKey = 'pid';
}