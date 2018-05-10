<?php

namespace App\Models;

class ProjectTemplate extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_template';
    protected $primaryKey = 'tid';
    public $timestamps = false;
}
