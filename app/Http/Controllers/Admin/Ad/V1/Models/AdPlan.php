<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 广告方案
 * App\Http\Controllers\Admin\Ad\V1\Models\Advertiser
 *
 * @property int $atiid
 * @property int $atid 广告行业
 * @property string $name 名称
 * @property string $icon 图标
 * @property string $info 详情
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\AdTrade $adTrade
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
        self::TYPE_BID_SCREEN => '小屏',
    ];

    //广告类型
    public function ad_trade(): BelongsTo
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }

}
