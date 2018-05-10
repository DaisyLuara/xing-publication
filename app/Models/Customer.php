<?php

namespace App\Models;


class Customer extends Model
{

    protected $fillable = ['name', 'phone', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
