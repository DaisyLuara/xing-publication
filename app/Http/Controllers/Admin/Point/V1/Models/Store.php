<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;

class Store extends Model
{

    protected $fillable = ['company_id', 'type', 'marketid', 'areaid', 'user_id', 'contract_id', 'name', 'logo', 'phone', 'address','description'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function market()
    {
        return $this->setConnection('ar')->belongsTo(Market::class, 'marketid', 'marketid');
    }

    public function area()
    {
        return $this->setConnection('ar')->belongsTo(Area::class, 'areaid', 'areaid');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'contract_id', 'id');
    }

}
