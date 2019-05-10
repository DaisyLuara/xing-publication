<?php

namespace App\Http\Controllers\Admin\Ad\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Ad\V1\Models\Advertisement
 *
 * @property int $aid
 * @property int $atid 行业ID
 * @property int $atiid 广告主ID
 * @property string $name 名称
 * @property int $uid 归属用户
 * @property string $img 介绍图
 * @property string $type static：静态图 gif：gif fps：帧序列 video：视频
 * @property string $link 附件
 * @property int $size 附件大小
 * @property int $fps 帧率
 * @property int $isad 0: 不是 1：是
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\AdTrade $adTrade
 * @property-read \App\Http\Controllers\Admin\Ad\V1\Models\Advertiser $advertiser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereAid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereAtid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereAtiid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereFps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereIsad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Ad\V1\Models\Advertisement whereUid($value)
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
        'atiid',
        'name',
        'img',
        'type',
        'link',
        'size',
        'fps',
        'isad',
        'date',
        'clientdate'
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class, 'z', 'z');
    }

    public function adTrade()
    {
        return $this->belongsTo(AdTrade::class, 'atid', 'atid');
    }
}
