<?php

namespace App\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $guard_name = 'shop';

    protected $fillable = ['name', 'position', 'phone', 'telephone', 'company_id', 'password'];

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
