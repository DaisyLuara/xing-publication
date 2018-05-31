<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;

class AdProduct extends Model
{
    protected $connection = 'ar';
    public $table = 'ar_product_ad_list';
    protected $primaryKey = 'adid';
    public $timestamps = false;

    public $fillable = [
        'adid',
        'url',
        'oid'
    ];

    public function adTrade()
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }
}
