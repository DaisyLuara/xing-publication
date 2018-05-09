<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
    protected $connection='ar';
    public $table='push';

    public function point(){
        return $this->belongsTo(Point::class,'oid','oid');
    }

    public function project(){
        return $this->belongsTo(Project::class,'alias','versionname');
    } 
}
