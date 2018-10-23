<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $fillable = ['name', 'phone', 'company_id', 'password'];

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
