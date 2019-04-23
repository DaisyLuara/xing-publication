<?php

namespace App\Http\Controllers\Admin\Activity\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Activity\V1\Models\Activity
 *
 * @property int $acid
 * @property int $cid 公司id
 * @property string $title 标题
 * @property string $txt 简介
 * @property int $nums 参与人数
 * @property string $tabs 标签
 * @property string $image 宣传图
 * @property string $video 视频
 * @property string $loc 活动地址
 * @property string $gps 地址坐标
 * @property string $awardkey 奖品KEY
 * @property int $marketid 0为通用
 * @property int $oid 0为通用
 * @property string|null $xids 荣誉限定
 * @property string|null $info 活动介绍
 * @property string $infolink 活动介绍链接
 * @property string|null $ps 活动须知
 * @property string $pslink 活动须知链接
 * @property int $pass 状态
 * @property string $date
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereAcid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereAwardkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereGps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereInfolink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereLoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereNums($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity wherePass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity wherePs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity wherePslink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereTabs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereTxt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Activity\V1\Models\Activity whereXids($value)
 * @mixin \Eloquent
 */
class Activity extends Model
{
    protected $connection = 'ar';
    protected $table = 'avr_activity';
    protected $primaryKey = 'acid';
    public $timestamps = false;

    public $fillable = [
        'acid',
        'cid',
        'title',
        'txt',
        'tabs',
        'image',
        'video',
        'loc',
        'gps',
        'awardkey',
        'oid',
        'info',
        'infolink',
        'ps',
        'pslink',
        'date',
        'clientdate',
    ];

}
