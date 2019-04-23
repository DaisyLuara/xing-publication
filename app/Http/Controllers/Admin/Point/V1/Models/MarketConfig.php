<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Customer;
use App\Models\Model;
use App\Models\User;

/**
 * App\Http\Controllers\Admin\Point\V1\Models\MarketConfig
 *
 * @property int $id
 * @property int|null $company_id 商场所属公司id
 * @property int|null $bd_user_id 所属BD
 * @property int|null $contract_id 合同ID
 * @property int|null $write_off_customer_id 商户核销人ID
 * @property int|null $media_id 商场logo图片ID
 * @property string|null $phone 商场电话
 * @property string|null $address 商场地址
 * @property string|null $description 商场描述
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\Contract|null $adContract
 * @property-read \App\Models\User|null $bdUser
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\Company|null $company
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market $market
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media|null $media
 * @property-read \App\Models\Customer|null $writeOffCustomer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereBdUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketConfig whereWriteOffCustomerId($value)
 * @mixin \Eloquent
 */
class MarketConfig extends Model
{
    protected $table = 'market_config';

    protected $fillable = ['company_id', 'type', 'bd_user_id', 'marketid', 'areaid', 'user_id', 'contract_id', 'write_off_customer_id', 'name', 'media_id', 'phone', 'address','description'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'id', 'marketid');
    }

    public function adContract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }

    public function bdUser()
    {
        return $this->belongsTo(User::class, 'bd_user_id', 'id');
    }

    public function writeOffCustomer()
    {
        return $this->belongsTo(Customer::class, 'write_off_customer_id', 'id');
    }


}
