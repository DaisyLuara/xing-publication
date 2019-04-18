<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;


/**
 * App\Http\Controllers\Admin\Point\V1\Models\PointContract
 *
 * @property int $oid 点位ID
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
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point $point
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereAdAds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereAdIstar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereContractCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereContractNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereContractPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereContractUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereEnterEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereEnterSdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereExchangeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereOperEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereOperSdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract wherePay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointContract whereType($value)
 * @mixin \Eloquent
 */
class PointContract extends ArModel
{
    public $table = 'avr_official_contract';
    protected $primaryKey = 'oid';

    protected $fillable = [
        'type', 'contract', 'contract_company', 'contract_num', 'contract_user', 'contract_phone', 'mode', 'ad_istar',
        'enter_sdate', 'enter_edate', 'oper_sdate', 'oper_edate',
        'ad_ads', 'exchange_num', 'date', 'clientdate'
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

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }
}
