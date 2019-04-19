<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Customer;
use App\Models\Model;
use App\Models\User;

/**
 * App\Http\Controllers\Admin\Point\V1\Models\Store
 *
 * @property int $id
 * @property int $company_id
 * @property string $type 商户类型-1:自营, 2:连锁
 * @property int|null $marketid 场地ID
 * @property int $areaid 区域ID
 * @property int|null $user_id 所属BD
 * @property int|null $contract_id 合同ID
 * @property int|null $write_off_customer_id 商场核销人ID
 * @property string $name 门店名称
 * @property int|null $media_id 门店logo图片ID
 * @property string|null $phone 门店电话
 * @property string|null $address 门店地址
 * @property string|null $description 门店描述
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Area $area
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\Company $company
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\Contract|null $contract
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market|null $market
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media|null $media
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Customer|null $writeOffCustomer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereAreaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Store whereWriteOffCustomerId($value)
 * @mixin \Eloquent
 */
class Store extends Model
{

    protected $fillable = ['company_id', 'type', 'marketid', 'areaid', 'user_id', 'contract_id', 'write_off_customer_id', 'name', 'media_id', 'phone', 'address','description'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaid', 'areaid');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function writeOffCustomer()
    {
        return $this->belongsTo(Customer::class, 'write_off_customer_id', 'id');
    }

}
