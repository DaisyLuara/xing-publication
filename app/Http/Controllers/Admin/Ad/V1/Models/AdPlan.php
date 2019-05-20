<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Customer;
use App\Models\Model;
use App\Models\User;
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
 * @property-read \App\Models\Customer $create_customer
 * @property-read \App\Models\User $create_user
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
        'hardware',
        'tmode',
        'z',
        'date',
        'clientdate'
    ];

    public const TYPE_BID_SCREEN = 'program';
    public const TYPE_SMALL_SCREEN = 'ads';
    public static $typeMapping = [
        self::TYPE_BID_SCREEN => '节目广告',
        self::TYPE_SMALL_SCREEN => '小屏广告',
    ];

    public const MODE_FULLSCREEN = 'fullscreen';
    public const MODE_UNMANNED = 'unmanned';
    public const MODE_QRCODE = 'qrcode';
    public const MODE_FLOATING = 'floating';
    public static $modeMapping = [
        self::MODE_FULLSCREEN => '全屏显示',
        self::MODE_UNMANNED => '无人互动',
        self::MODE_QRCODE => '二维码页面',
        self::MODE_FLOATING => '浮窗显示',
    ];

    public const ORI_CENTER = 'center';
    public const ORI_TOP = 'top';
    public const ORI_BOTTOM = 'bottom';
    public const ORI_LEFT_TOP = 'left_top';
    public const ORI_LEFT = 'left';
    public const ORI_LEFT_BOTTOM = 'left_bottom';
    public const ORI_RIGHT_TOP = 'right_top';
    public const ORI_RIGHT = 'right';
    public const ORI_RIGHT_BOTTOM = 'right_bottom';
    public static $OriMapping = [
        self::ORI_CENTER => '居中',
        self::ORI_TOP => '顶部居中',
        self::ORI_BOTTOM => '底部居中',
        self::ORI_LEFT_TOP => '左上角',
        self::ORI_LEFT => '左侧居中',
        self::ORI_LEFT_BOTTOM => '左下角',
        self::ORI_RIGHT_TOP => '右上角',
        self::ORI_RIGHT => '右侧居中',
        self::ORI_RIGHT_BOTTOM => '右下角',
    ];

    public const TMODE_DIV = 'div';
    public const TMODE_HOURS = 'hours';
    public static $tmodeMapping = [
        self::TMODE_DIV => '自定义',
        self::TMODE_HOURS => '小时',
    ];


    //广告类型
    public function ad_trade(): BelongsTo
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }

    //广告素材排期
    public function advertisements(): BelongsToMany
    {
        return $this->belongsToMany(Advertisement::class, 'avr_ad_trade_time', 'atiid', 'aid')
            ->withPivot('id', 'mode', 'ori', 'screen', 'cdshow', 'shm', 'ehm', 'ktime', 'only', 'visiable', 'date', 'clientdate');
    }

    //创建人
    public function create_user(): BelongsTo
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'z', 'z');
    }

    //创建人
    public function create_customer(): BelongsTo
    {
        return $this->setConnection('mysql')->belongsTo(Customer::class, 'z', 'z');
    }

}
