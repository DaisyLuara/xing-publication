<?php

namespace App\Http\Controllers\Admin\Company\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Models\Model;
use App\Models\Customer;


class Store extends Model
{

    protected $fillable = ['company_id', 'type', 'market_id', 'area_id', 'name', 'logo', 'phone', 'address','description'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id', 'marketid');
    }
}
