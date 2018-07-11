<?php

namespace App\Http\Controllers\Admin\Company\V1\Models;

use App\Models\User;
use App\Models\Model;


class Company extends Model
{

    protected $fillable = ['name', 'address', 'status', 'user_id', 'trade_id'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function isCompanyCustomer($model)
    {
        return $this->id == $model->company_id;
    }
}
