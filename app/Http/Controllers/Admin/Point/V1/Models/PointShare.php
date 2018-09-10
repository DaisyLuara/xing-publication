<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;


class PointShare extends ArModel
{
    public $table = 'avr_official_share';
    protected $primaryKey = 'oid';

    protected $fillable = [
        'marketid', 'site', 'vipad', 'ad', 'agent', 'offer', 'offer_off',
        'mad', 'mad_off', 'play', 'play_off', 'qrcode', 'qrcode_off', 'wx_pa',
        'wx_pa_off', 'wx_mp', 'wx_mp_off', 'app', 'app_off', 'phone', 'phone_off',
        'coupon', 'coupon_off', 'date', 'clientdate'
    ];

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }
}
