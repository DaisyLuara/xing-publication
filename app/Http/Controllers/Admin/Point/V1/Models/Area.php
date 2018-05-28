<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;


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
