<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 广告投放
 *
 * @property int $aoid
 * @property int $atiid 方案ID
 * @property int $marketid 商场ID,0为通用
 * @property int $oid 门店ID,0为通用
 * @property int $piid 节目id
 * @property int $sdate 开始日期
 * @property int $edate 结束日期
 * @property int $visiable 1：运营中
 * @property int $only 1:唯一
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\AdPlan $ad_plan
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market $market
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point $point
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereAoid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereAtiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch wherePiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereSdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch whereVisiable($value)
 * @mixin \Eloquent
 */
class AdLaunch extends Model
{

    protected $connection = 'ar';
    public $table = 'avr_ad_oid';
    protected $primaryKey = 'aoid';
    public $timestamps = false;

    public $fillable = [
        'atiid',
        'marketid',
        'oid',
        'piid',
        'sdate',
        'edate',
        'visiable', //1 投放中 0 下架
        'only',  //1 唯一性 0 非唯一
        'date',
        'clientdate'
    ];

    //广告方案
    public function ad_plan(): BelongsTo
    {
        return $this->belongsTo(AdPlan::class, 'atiid', 'atiid');
    }

    //场地
    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }

    //点位
    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    //节目
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'piid', 'id');
    }

}
