<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Models\Model;

class MallcooConfig extends Model
{
    protected $table = 'mallcoo_config';

    protected $fillable = [];

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }


}
