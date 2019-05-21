<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Customer;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * 广告素材
 *
 * @property int $aid
 * @property int $atid 分类ID
 * @property string $name 名称
 * @property string|null $z 密钥
 * @property string $img 介绍图
 * @property string $type static：静态图 gif：gif fps：帧序列 video：视频
 * @property string $link 附件
 * @property int $size 附件大小
 * @property int $fps 帧率
 * @property int $isad 0: 不是 1：是
 * @property int $pass 8:准备中
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\AdTrade $ad_trade
 * @property-read \App\Models\User|null $create_user
 * @property-read \App\Models\User|null $create_customer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereAid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereAtid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereFps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereIsad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement wherePass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereZ($value)
 * @mixin \Eloquent
 */
class Advertisement extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_ad_info';
    protected $primaryKey = 'aid';
    public $timestamps = false;

    public $fillable = [
        'atid',
        'name',
        'z',
        'img',
        'type',
        'link',
        'size',
        'fps',
        'isad',
        'pass',
        'date',
        'clientdate'
    ];

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

    //广告行业
    public function ad_trade(): BelongsTo
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }

//static：静态图 gif：gif fps：帧序列 video：视频

    public const TYPE_STATIC = 'static';
    public const TYPE_GIF = 'gif';
    public const TYPE_FPS = 'fps';
    public const TYPE_VIDEO = 'video';

    public static $typeMapping = [
        self::TYPE_STATIC => '静态图',
        self::TYPE_GIF => 'gif',
        self::TYPE_FPS => '帧序列',
        self::TYPE_VIDEO => '视频',
    ];
}
