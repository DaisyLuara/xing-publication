<?php

namespace App\Models;

class Point extends Model
{

    protected $connection = 'ar';
    public $table = 'avr_official';
    protected $primaryKey = 'oid';

}
