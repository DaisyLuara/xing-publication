<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;


/**
 * App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule
 *
 * @property int $tviid
 * @property int $cid 公司ID
 * @property int $pid 产品ID
 * @property int $tvid 模板ID
 * @property int $plid 子产品ID
 * @property int $shm 开始时间
 * @property int $ehm 结束时间
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule whereEhm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule wherePlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule whereShm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule whereTvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule whereTviid($value)
 * @mixin \Eloquent
 */
class ProjectLaunchTplSchedule extends Model
{
    protected $connection = 'ar';
    public $table = 'istar_tv_info';
    protected $primaryKey = 'tviid';
    public $timestamps = false;

    public $fillable = [
        'cid',
        'pid',
        'tvid',
        'plid',
        'shm',
        'ehm',
        'sdate',
        'edate',
        'div_tvid',
        'date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'plid', 'id');
    }
}
