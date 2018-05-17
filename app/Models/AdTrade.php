<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
