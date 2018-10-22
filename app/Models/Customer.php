<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Http\Controllers\Admin\Company\V1\Models\Company;

class Customer extends Authenticatable implements JWTSubject
{

    protected $fillable = ['name', 'position','phone', 'company_id', 'password'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
