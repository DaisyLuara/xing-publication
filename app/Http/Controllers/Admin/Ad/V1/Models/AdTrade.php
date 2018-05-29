<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;

class AdTrade extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_trade';
    protected $primaryKey = 'atid';
    public $timestamps = false;

    public $fillable = [
        'name',
        'icon',
        'date',
        'clientdate',
    ];
}
