<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdLaunch extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_oid';
    protected $primaryKey = 'aoid';

    public function adTrade()
    {
        return $this->hasOne(AdTrade::class, 'atid', 'atid');
    }

    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, 'atiid', 'atiid');
    }

    public function advertisement()
    {
        return $this->hasOne(Advertisement::class, 'aid', 'aid');
    }

    public function area()
    {
        return $this->hasOne(Area::class,'areaid','areaid');
    }

    public function market()
    {
        return $this->hasOne(Market::class,'marketid','marketid');
    }

    public function point()
    {
        return $this->hasOne(Point::class, 'oid', 'oid');
    }
}
