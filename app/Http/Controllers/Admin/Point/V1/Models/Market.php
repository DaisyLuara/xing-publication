<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\ArModel;


class Market extends ArModel
{
    public $table = 'avr_official_market';
    protected $primaryKey = 'marketid';

    protected $fillable = [
        'name', 'info', 'icon', 'image', 'type', 'lat', 'lng', 'count', 'areaid', 'date',
        'clientdate'
    ];


    public function points()
    {
        return $this->hasMany(Point::class, 'marketid', 'marketid');
    }

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

    public function company()
    {
        return $this->belongsTo(Company::class, 'companyid', 'id');
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'marketid', 'marketid');
    }

    public function marketConfig()
    {
        return $this->setConnection('mysql')->hasOne(MarketConfig::class,'id', 'marketid');
    }
}
