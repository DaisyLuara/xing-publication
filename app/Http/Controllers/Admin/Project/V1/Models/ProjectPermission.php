<?php

namespace App\Http\Controllers\Admin\Project\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission
 *
 * @property int $id
 * @property int|null $uid
 * @property string $z 用户标识
 * @property int $pid 产品ID
 * @property string $date
 * @property int $clientdate
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\Project $projects
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Project\V1\Models\ProjectPermission whereZ($value)
 * @mixin \Eloquent
 */
class ProjectPermission extends Model
{
    protected $connection = 'ar';
    public $table = 'admin_per_pid';
    public $timestamps = false;

    public function projects()
    {
        return $this->belongsTo(Project::class, 'pid', 'pid');
    }
}
