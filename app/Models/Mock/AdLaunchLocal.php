<?php

namespace App\Models\Mock;

use Illuminate\Database\Eloquent\Model;

class AdLaunchLocal extends Model
{
    public $table = 'ad_launches';
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
}
