<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;


class MarketContract extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_official_market_contract';
    protected $primaryKey = 'marketid';

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }
}
