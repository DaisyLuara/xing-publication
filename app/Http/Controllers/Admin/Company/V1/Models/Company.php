<?php

namespace App\Http\Controllers\Admin\Company\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Models\User;
use App\Models\Model;
use App\Models\Customer;


class Company extends Model
{

    protected $fillable = ['name', 'internal_name', 'address', 'category','status', 'user_id', 'trade_id', 'description', 'logo'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function markets()
    {
        return $this->setConnection('ar')->hasMany(Market::class, 'companyid', 'id');
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'company_id', 'id');
    }

    public function isCompanyCustomer($model)
    {
        return $this->id == $model->company_id;
    }
}
