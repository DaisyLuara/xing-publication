<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Http\Controllers\Admin\Attribute\V1\Models\Attribute;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Models\ArModel;

/**
 * App\Http\Controllers\Admin\Point\V1\Models\Point
 *
 * @property int $oid
 * @property int $cid 公司id
 * @property int $areaid 区域ID
 * @property int $marketid 商场ID
 * @property int $sid 场景id
 * @property int $bd_uid bd账号
 * @property string|null $bd_z
 * @property int $site_uid 场地主uid
 * @property string|null $site_z
 * @property string $code 编码
 * @property int $hours 通电时间
 * @property int $shours 营业开始时间
 * @property int $ehours 营业结束时间
 * @property int $weekday 工作日运行
 * @property int $weekend 周末运行
 * @property int $visiable 0：隐藏 1：显示
 * @property string $name 名称
 * @property string $info 介绍
 * @property string $icon 图标
 * @property string $type free:免费入驻,pay:付费入驻,adpart:广告分成,sell:出售,lease:租借,activity:活动,agent:代理
 * @property string $mode none:不共享,all:全共享,site:场地/设备主独享,advip:Vip广告主独享,ads:韭菜独享,agent:代理独享
 * @property string $date
 * @property int $clientdate
 * @property string $lat
 * @property string $lng
 * @property string $geo_hash
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Area $area
 * @property-read \Baum\Extensions\Eloquent\Collection|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute[] $attribute
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\PointContract $contract
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market $market
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Project\V1\Models\Project[] $projects
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Scene $scene
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\PointShare $share
 * @method static \Illuminate\Database\Eloquent\Builder|Point newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Point newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Point query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereAreaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereBdUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereBdZ($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereEhours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereGeoHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereShours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereSiteUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereSiteZ($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereVisiable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereWeekday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Point whereWeekend($value)
 * @mixin \Eloquent
 */
class Point extends ArModel
{

    public $table = 'avr_official';
    protected $primaryKey = 'oid';

    protected $fillable = [
        'areaid', 'marketid', 'sid', 'bd_uid', 'site_uid',
        'hours', 'shours', 'ehours', 'weekday', 'weekend',
        'visiable', 'name', 'info', 'icon', 'type', 'date',
        'clientdate', 'lat', 'lng', 'geo_hash', 'bd_z', 'site_z'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'istar_tv_oid', 'oid', 'default_plid');
    }

    public function scene()
    {
        return $this->belongsTo(Scene::class, 'sid', 'sid');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaid', 'areaid');
    }

    public function share()
    {
        return $this->hasOne(PointShare::class, 'oid', 'oid');
    }

    public function contract()
    {
        return $this->hasOne(PointContract::class, 'oid', 'oid');
    }

    public function attribute()
    {
        return $this->belongsToMany(Attribute::class, 'xs_point_attributes', 'point_id', 'attribute_id')->where('parent_id', '=', '5');
    }

}
