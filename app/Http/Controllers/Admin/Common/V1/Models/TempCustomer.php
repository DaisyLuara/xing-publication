<?php

namespace App\Http\Controllers\Admin\Common\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Common\V1\Models\TempCustomer
 *
 * @property int $id
 * @property string $name 姓名
 * @property string|null $mobile
 * @property string $address
 * @property int $age
 * @property int $gender 0
 * @property int $oid
 * @property string $belong
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\TempCustomer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TempCustomer extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'address',
        'age',
        'gender',
        'oid',
        'belong',
    ];

}
