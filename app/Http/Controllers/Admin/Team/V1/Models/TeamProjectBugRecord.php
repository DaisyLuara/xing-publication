<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;
use App\Models\User;

/**
 * App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord
 *
 * @property int $id
 * @property int $team_project_id 项目ID
 * @property string $project_name 项目节目名称
 * @property string $belong 项目节目标识
 * @property int $user_id 用户ID
 * @property string|null $duty 用户职责:tester_quality测试|operation_quality运营
 * @property int $bug_num bug 数量
 * @property string|null $date bug记录时间 只能是1月1 4月1 7月1 10月1
 * @property string|null $occur_date bug事件发生日期
 * @property string|null $description bug简单描述
 * @property int $recorder_id 记录用户ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $recorder
 * @property-read \App\Http\Controllers\Admin\Team\V1\Models\TeamProject $team_project
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereBugNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereDuty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereOccurDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereRecorderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereTeamProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectBugRecord whereUserId($value)
 * @mixin \Eloquent
 */
class TeamProjectBugRecord extends Model
{
    protected $fillable = [
        'team_project_id',
        'project_name',
        'belong',
        'user_id',
        'duty',
        'bug_num',
        'date',
        'occur_date',
        'recorder_id',
        'description',
    ];

    public function recorder(){
        return $this->belongsTo(User::class,'recorder_id','id');
    }

    public function team_project(){
        return $this->belongsTo(TeamProject::class,'team_project_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}