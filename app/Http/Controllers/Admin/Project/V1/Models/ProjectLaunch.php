<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch
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
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point $point
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\Project $project
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl $template
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDay1Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDay2Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDay3Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDay4Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDay5Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDay6Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDay7Tvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDefaultPlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereDivTvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereSdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereTvoid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereWeekdayTvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch whereWeekendTvid($value)
 * @mixin \Eloquent
 */
class ProjectLaunch extends Model
{
    protected $connection = 'ar';
    public $table = 'istar_tv_oid';
    protected $primaryKey = 'tvoid';
    public $timestamps = false;

    public $fillable = [
        'cid',
        'pid',
        'oid',
        'default_plid',
        'weekday_tvid',
        'weekend_tvid',
        'day1_tvid',
        'day2_tvid',
        'day3_tvid',
        'day4_tvid',
        'day5_tvid',
        'day6_tvid',
        'day7_tvid',
        'sdate',
        'edate',
        'div_tvid',
        'date'
    ];

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'default_plid', 'id');
    }

    public function template()
    {
        return $this->belongsTo(ProjectLaunchTpl::class,'div_tvid','tvid');
    }

}
