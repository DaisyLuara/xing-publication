<?php

namespace App\Models;

use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Authenticatable implements JWTSubject
{
    use HasRoles;

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

    public function role()
    {
        return $this->morphToMany(
            Role::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        );
    }
}
