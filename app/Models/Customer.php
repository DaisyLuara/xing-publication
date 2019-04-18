<?php

namespace App\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Customer
 * @package App\Models
 * @property int $id
 * @property int $company_id
 * @property string $name 客户名称
 * @property string|null $position 职务
 * @property string|null $phone
 * @property string|null $telephone 座机电话
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $avatar
 * @property string|null $introduction
 * @property int $notification_count
 * @property string|null $weixin_openid
 * @property string|null $weixin_unionid
 * @property int|null $ar_user_id 星视度用户ID
 * @property string|null $z ar用户标识
 * @property string|null $deleted_at
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\Company $company
 * @property-read \Baum\Extensions\Eloquent\Collection|\App\Http\Controllers\Admin\Privilege\V1\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Privilege\V1\Models\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereArUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereIntroduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereNotificationCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereWeixinOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereWeixinUnionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereZ($value)
 * @mixin \Eloquent
 */
class Customer extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $guard_name = 'shop';

    protected $fillable = ['name', 'position', 'phone', 'telephone', 'company_id', 'password','z'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
