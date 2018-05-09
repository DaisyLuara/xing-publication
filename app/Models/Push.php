<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
    protected $connection='ar';
    public $table='push';

    function point(){
        return $this->belongsTo(Point::class,'oid','oid');
    }
}
