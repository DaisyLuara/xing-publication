<?php

namespace App\Models;


class Area extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_official_area';
    protected $primaryKey = 'areaid';

    public function markets()
    {
        return $this->hasMany(Market::class, 'areaid', 'areaid');
    }
}
