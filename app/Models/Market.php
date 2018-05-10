<?php

namespace App\Models;


class Market extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_official_market';
    protected $primaryKey = 'marketid';

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaid', 'areaid');
    }
}
