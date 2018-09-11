<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;


class MarketShare extends ArModel
{
    public $table = 'avr_official_market_share';
    protected $primaryKey = 'marketid';

    protected $fillable = [
        'marketid', 'site', 'vipad', 'ad', 'agent', 'offer', 'offer_off',
        'mad', 'mad_off', 'play', 'play_off', 'qrcode', 'qrcode_off', 'wx_pa',
        'wx_pa_off', 'wx_mp', 'wx_mp_off', 'app', 'app_off', 'phone', 'phone_off',
        'coupon', 'coupon_off', 'date', 'clientdate'
    ];

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }
}
