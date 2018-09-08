<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;


class MarketShare extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_official_market_share';
    protected $primaryKey = 'marketid';

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }
}
