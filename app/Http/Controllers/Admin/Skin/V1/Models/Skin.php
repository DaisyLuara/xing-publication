<?php

namespace App\Http\Controllers\Admin\Skin\V1\Models;

use App\Models\ArModel;

/**
 * App\Http\Controllers\Admin\Skin\V1\Models\Skin
 *
 * @property int $bid
 * @property string $fun 功能
 * @property string $name 名称
 * @property string|null $icon 图标
 * @property int $piid 节目限制
 * @property string|null $video 视频介绍
 * @property int $marketid 场地限制
 * @property int $oid 点位限制
 * @property int $credits 积分
 * @property int $rmb 金币
 * @property string|null $url 皮肤包
 * @property int $size 包大小
 * @property string|null $z 用户密钥
 * @property int $nums 订购数量
 * @property string|null $fopid 回调Id
 * @property int $fopdate 校验时间
 * @property int $code 返回码
 * @property int $pass 8:准备中
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereBid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereFopdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereFopid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereFun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereNums($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin wherePass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin wherePiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereRmb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Skin\V1\Models\Skin whereZ($value)
 * @mixin \Eloquent
 */
class Skin extends ArModel
{
    //节目皮肤
    public $table = 'avr_goods_bag';
    protected $primaryKey = 'bid';

    protected $fillable = [
        'fun',      //功能 默认 program
        'name',     //名称
        'icon',
        'video',
        'piid',     //节目限制 默认0
        'marketid', //场地限制 默认0
        'oid',      //点位限制 默认0
        'credits',  //积分 默认0
        'rmb',      //金币 默认0
        'url',      //皮肤包
        'size',
        'nums',     //订购数量
        'fopid',    //回调Id
        'fopdate',  //校验时间 默认0
        'code',     //返回码 默认0
        'pass',     //准备中 默认8

    ];

    public const PASS_TRUE = 1;
    public const PASS_PREPARE = 8;

    public static $passMapping = [
        self::PASS_TRUE => '通过',
        self::PASS_PREPARE => '准备中',
    ];

    public function getPassText()
    {
        return self::$passMapping[$this->pass] ?? '未知';
    }


}
