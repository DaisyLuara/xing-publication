<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 广告方案
 *
 * @property int $atiid
 * @property int $atid 广告类型
 * @property string $name 名称
 * @property string $icon 图标
 * @property string|null $info 备注
 * @property string $type program：节目 ads：小屏
 * @property string $z 密钥
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\AdTrade $ad_trade
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement[] $advertisements
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan whereAtid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan whereAtiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\AdPlan whereZ($value)
 * @mixin \Eloquent
 */
class AdPlan extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_trade_info';
    protected $primaryKey = 'atiid';
    public $timestamps = false;

    public $fillable = [
        'atid',
        'name',
        'icon',
        'info',
        'type',
//        'z',
        'date',
        'clientdate'
    ];

    public const TYPE_BID_SCREEN = 'program';
    public const TYPE_SMALL_SCREEN = 'ads';

    public static $typeMapping = [
        self::TYPE_BID_SCREEN => '大屏',
        self::TYPE_SMALL_SCREEN => '小屏',
    ];

    //广告类型
    public function ad_trade(): BelongsTo
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }

    //广告素材排期
    public function advertisements(): BelongsToMany
    {
        return $this->belongsToMany(Advertisement::class, 'avr_ad_trade_time', 'atiid','aid')
            ->withPivot('mode', 'ori','screen','cdshow','shm','ehm','ktime','only','date','clientdate');
    }

}
