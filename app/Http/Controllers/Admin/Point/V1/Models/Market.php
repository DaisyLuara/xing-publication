<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;


class Market extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_official_market';
    protected $primaryKey = 'marketid';

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaid', 'areaid');
    }

    public function contract()
    {
        return $this->hasOne(MarketContract::class, 'marketid', 'marketid');
    }

    public function share()
    {
        return $this->hasOne(MarketShare::class, 'marketid', 'marketid');
    }
}
