<?php

namespace App\Models;


class Scene extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_official_scene';
    protected $primaryKey = 'sid';

    public function points()
    {
        return $this->hasMany(Point::class, 'sid', 'sid');
    }
}
