<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Customer;
use App\Models\Model;
use App\Models\User;

class MarketConfig extends Model
{
    protected $table = 'market_config';

    protected $fillable = ['company_id', 'type', 'bd_user_id', 'marketid', 'areaid', 'user_id', 'contract_id', 'write_off_customer_id', 'name', 'media_id', 'phone', 'address','description'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'id', 'marketid');
    }

    public function adContract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }

    public function bdUser()
    {
        return $this->belongsTo(User::class, 'bd_user_id', 'id');
    }

    public function writeOffCustomer()
    {
        return $this->belongsTo(Customer::class, 'write_off_customer_id', 'id');
    }


}
