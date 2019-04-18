<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;


/**
 * App\Http\Controllers\Admin\Point\V1\Models\PointProject
 *
 * @property int $tvoid
 * @property int $cid 公司ID
 * @property int $pid 产品ID
 * @property int $oid 门店ID,0为通用
 * @property int $default_plid 默认子产品ID
 * @property int $day1_tvid 周一模板
 * @property int $day2_tvid 周二模板
 * @property int $day3_tvid 周三模板
 * @property int $day4_tvid 周四模板
 * @property int $day5_tvid 周五模板
 * @property int $day6_tvid 周六模板
 * @property int $day7_tvid 周日模板
 * @property int $weekday_tvid 工作日默认模板ID
 * @property int $weekend_tvid 周末默认模板ID
 * @property int $sdate 开始日期
 * @property int $edate 结束日期
 * @property int $div_tvid 自定义默认模板ID
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDay1Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDay2Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDay3Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDay4Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDay5Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDay6Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDay7Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDefaultPlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereDivTvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereSdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereTvoid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereWeekdayTvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\PointProject whereWeekendTvid($value)
 * @mixin \Eloquent
 */
class PointProject extends Model
{
    //@todo 和另外一个表 定义重复了 ProjectLaunch
    protected $connection = 'ar';
    public $table = 'istar_tv_oid';
    protected $primaryKey = 'tvoid';
}
