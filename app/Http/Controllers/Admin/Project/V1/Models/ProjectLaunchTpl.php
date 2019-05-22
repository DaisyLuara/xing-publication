<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl
 *
 * @property int $tvid
 * @property int $cid 公司ID
 * @property int $pid 产品ID
 * @property int $oid 门店ID,0为通用
 * @property string $name 名称
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule[] $schedules
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectLaunchTpl whereTvid($value)
 * @mixin \Eloquent
 */
class ProjectLaunchTpl extends Model
{
    protected $connection = 'ar';
    public $table = 'istar_tv';
    protected $primaryKey = 'tvid';
    public $timestamps = false;

    public $fillable = [
        'cid',
        'pid',
        'oid',
        'name',
        'date',
        'clientdate',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(ProjectLaunchTplSchedule::class, 'tvid', 'tvid');
    }
}

