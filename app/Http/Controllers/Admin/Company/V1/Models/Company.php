<?php

namespace App\Http\Controllers\Admin\Company\V1\Models;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Models\User;
use App\Models\Model;
use App\Models\Customer;

/**
 * Class Company
 *
 * @package App\Http\Controllers\Admin\Company\V1\Models
 * @property int $id
 * @property int $bd_user_id
 * @property int $user_id
 * @property string $name 公司全称
 * @property string|null $internal_name
 * @property string $address 公司地址
 * @property int $category 0:客户，1：供应商
 * @property string $status 1待审核 2.待合作 3合作中 4已结束
 * @property int $trade_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description 商户描述
 * @property string $logo 商户logo
 * @property int|null $logo_media_id 商户图片
 * @property-read \App\Models\User|null $bdUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Company\V1\Models\Company[] $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Customer[] $customers
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media|null $media
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\Company $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Point\V1\Models\Store[] $stores
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereBdUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereInternalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereLogoMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereTradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\Company whereUserId($value)
 * @mixin \Eloquent
 */
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
