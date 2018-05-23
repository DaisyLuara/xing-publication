<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AdLaunch extends Model
{

    protected $connection = 'ar';
    public $table = 'avr_ad_oid';
    protected $primaryKey = 'aoid';
    public $timestamps = false;

    public $fillable = [
        'atid',
        'atiid',
        'aid',
        'areaid',
        'marketid',
        'oid',
        'ktime',
        'sdate',
        'edate',
        'date',
        'clientdate'
    ];

    public function adTrade()
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class, 'atiid', 'atiid');
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'aid', 'aid');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaid', 'areaid');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }
}
