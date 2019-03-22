<?php

namespace App\Http\Controllers\Admin\Company\V1\Models;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Models\User;
use App\Models\Model;
use App\Models\Customer;


class Company extends Model
{

    protected $fillable = ['name', 'internal_name', 'address', 'category','status', 'user_id', 'trade_id', 'bd_user_id','parent_id', 'description', 'logo', 'logo_media_id'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function bdUser()
    {
        return $this->belongsTo(User::class, 'bd_user_id', 'id');
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

    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Company::class, 'parent_id', 'id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'logo_media_id', 'id');
    }

    public function isCompanyCustomer($model)
    {
        return $this->id == $model->company_id;
    }
}
