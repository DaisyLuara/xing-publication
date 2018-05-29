<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;

class Advertiser extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_trade_info';
    protected $primaryKey = 'atiid';
    public $timestamps = false;

    public $fillable = [
        'atid',
        'name',
        'icon',
        'info',
        'date',
        'clientdate'
    ];

    public function adTrade()
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }
}
