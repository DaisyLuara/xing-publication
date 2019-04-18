<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;
use App\Models\User;

/**
 * App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward
 *
 * @property int $id
 * @property int $user_id
 * @property string $project_name
 * @property string $belong
 * @property string $type interaction:交互技术,originality:节目创意,h5:H5开发,animation:设计动画,plan:节目统筹,tester:节目测试,operation:平台运营,system:平台奖
 * @property string $main_type 绩效类别
 * @property string|null $total
 * @property string|null $date 统计绩效的日期
 * @property string|null $get_date 延迟三个月后的发放日期
 * @property int $status 0 未发放 1 已发放 -1 因bug取消发放
 * @property int $team_project_id 节目项目ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Controllers\Admin\Team\V1\Models\TeamProject $team_project
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereGetDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereMainType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereTeamProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamPersonFutureReward whereUserId($value)
 * @mixin \Eloquent
 */
class TeamPersonFutureReward extends Model
{
    protected $fillable = [
        'user_id',
        'project_name',
        'belong',
        'total',
        'date',
        'type',
        'main_type',
        'get_date',
        'status',
        'team_project_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function team_project(){
        return $this->belongsTo(TeamProject::class, 'team_project_id', 'id');
    }
}

