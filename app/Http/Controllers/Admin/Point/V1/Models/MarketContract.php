<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;


/**
 * App\Http\Controllers\Admin\Point\V1\Models\MarketContract
 *
 * @property int $marketid 场地ID
 * @property string $type free:免费入驻,pay:付费入驻,sell:出售,lease:租借,activity:活动,agent:代理,tmp:过桥
 * @property int $contract 0:无 1：有
 * @property string|null $contract_company 合同公司
 * @property string|null $contract_num 合同编号
 * @property string|null $contract_user 合同联系人
 * @property string|null $contract_phone 合同联系方式
 * @property int $pay 租金元/年
 * @property int $enter_sdate 入驻开始时间
 * @property int $enter_edate 入驻结束时间
 * @property int $oper_sdate 运营开始时间
 * @property int $oper_edate 运营结束时间
 * @property string $mode none:无要求,part:分成,exchange:置换
 * @property float $ad_istar A类广告分成%
 * @property float $ad_ads B类广告分成%
 * @property int $exchange_num 置换节目数量
 * @property string $date
 * @property int $clientdate
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market $market
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereAdAds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereAdIstar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereContractCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereContractNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereContractPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereContractUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereEnterEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereEnterSdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereExchangeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereOperEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereOperSdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract wherePay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\MarketContract whereType($value)
 * @mixin \Eloquent
 */
class MarketContract extends ArModel
{
    public $table = 'avr_official_market_contract';
    protected $primaryKey = 'marketid';
    protected $fillable = [
        'marketid', 'type', 'contract', 'contract_company', 'contract_num', 'contract_user', 'contract_phone', 'pay',
        'enter_sdate', 'enter_edate', 'oper_sdate', 'oper_edate',
        'mode', 'ad_istar', 'ad_ads', 'exchange_num', 'date', 'clientdate',
    ];

    public function setEnterSdateAttribute($value)
    {
        $this->attributes['enter_sdate'] = strtotime($value);
    }

    public function setEnterEdateAttribute($value)
    {
        $this->attributes['enter_edate'] = strtotime($value);
    }

    public function setOperSdateAttribute($value)
    {
        $this->attributes['oper_sdate'] = strtotime($value);
    }

    public function setOperEdateAttribute($value)
    {
        $this->attributes['oper_edate'] = strtotime($value);
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }
}
