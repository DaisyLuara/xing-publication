<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;

class Advertisement extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_info';
    protected $primaryKey = 'aid';
    public $timestamps = false;

    public $fillable = [
        'atid',
        'atiid',
        'name',
        'img',
        'type',
        'link',
        'size',
        'fps',
        'isad',
        'date',
        'clientdate'
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class, 'atiid', 'atiid');
    }

    public function adTrade()
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }
}
