<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertiser extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_trade_info';
    protected $primaryKey = 'atiid';

    public function adTrade()
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }
}
