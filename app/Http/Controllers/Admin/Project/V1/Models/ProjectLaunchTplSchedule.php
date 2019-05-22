<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Http\Controllers\Admin\Skin\V1\Models\Skin;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
 * @property-read Skin $skin
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule whereEhm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule wherePlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule whereShm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule whereTvid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTplSchedule whereTviid($value)
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
        'bid',
        'shm',
        'ehm',
        'sdate',
        'edate',
        'div_tvid',
        'date'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'plid', 'id');
    }

    public function skin(): BelongsTo
    {
        return $this->belongsTo(Skin::class, 'bid', 'bid');
    }
}
