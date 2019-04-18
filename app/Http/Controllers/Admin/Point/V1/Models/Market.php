<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\ArModel;


/**
 * App\Http\Controllers\Admin\Point\V1\Models\Market
 *
 * @property int $marketid
 * @property int $areaid 区域id
 * @property int $pid 产品ID
 * @property int $cid 公司ID
 * @property int $uid 运维ID
 * @property string $name 名称
 * @property int $linkall 联动 0：关闭 1：开启
 * @property string $icon 图标
 * @property string|null $mkey 密钥
 * @property string|null $saas saas配对
 * @property string|null $psaas 顺序
 * @property string|null $h5 会员服务
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Area $area
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\Company $company
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\MarketContract $contract
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Point\V1\Models\Point[] $points
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\MarketShare $share
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Point\V1\Models\Store[] $stores
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereAreaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereH5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereLinkall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereMkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market wherePsaas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereSaas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Market whereUid($value)
 * @mixin \Eloquent
 */
class Market extends ArModel
{
    public $table = 'avr_official_market';
    protected $primaryKey = 'marketid';

    protected $fillable = [
        'name', 'info', 'icon', 'image', 'type', 'lat', 'lng', 'count', 'areaid', 'date',
        'clientdate'
    ];


    public function points()
    {
        return $this->hasMany(Point::class, 'marketid', 'marketid');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaid', 'areaid');
    }

    public function contract()
    {
        return $this->hasOne(MarketContract::class, 'marketid', 'marketid');
    }

    public function share()
    {
        return $this->hasOne(MarketShare::class, 'marketid', 'marketid');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companyid', 'id');
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'marketid', 'marketid');
    }

    public function marketConfig()
    {
        return $this->setConnection('mysql')->hasOne(MarketConfig::class,'id', 'marketid');
    }
}
