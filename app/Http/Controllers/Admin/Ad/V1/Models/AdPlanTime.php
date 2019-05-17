<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 广告方案排期
 *
 * @property int $id
 * @property int $atiid 方案ID
 * @property int $aid 素材ID
 * @property string $mode fullscreen：全屏
 * @property string $ori center：居中
 * @property int $screen 屏幕尺寸
 * @property int $cdshow 倒计时
 * @property int $shm 开始时间
 * @property int $ehm 结束时间
 * @property int $ktime 秒为单位
 * @property int $only 唯一
 * @property int $visiable 运营
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\AdPlan $ad_plan
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\Advertisement $advertisement
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereAid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereAtiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereCdshow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereEhm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereKtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereOri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereScreen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlanTime whereShm($value)
 * @mixin \Eloquent
 */
class AdPlanTime extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_trade_time';
    public $timestamps = false;

    public $fillable = [
        'atiid',
        'aid',
        'mode',
        'ori',
        'screen',
        'cdshow',
        'shm',
        'ehm',
        'ktime',
        'only',
        'visiable',
        'date',
        'clientdate'
    ];

    //广告方案
    public function ad_plan(): BelongsTo
    {
        return $this->belongsTo(AdPlan::class, 'atiid', 'atiid');
    }

    //广告素材
    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class, 'aid', 'aid');
    }



}
