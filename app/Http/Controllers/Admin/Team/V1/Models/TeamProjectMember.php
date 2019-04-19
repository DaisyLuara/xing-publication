<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember
 *
 * @property int $team_project_id
 * @property int $user_id
 * @property string $user_name
 * @property string $type interaction:交互技术,originality:节目创意,h5:H5开发,animation:设计动画,plan:节目统筹,tester:节目测试,operation:平台运营
 * @property string $rate 比例
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember whereTeamProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProjectMember whereUserName($value)
 * @mixin \Eloquent
 */
class TeamProjectMember extends Model
{

    public static $team_zhizao = [
        'originality',
        'plan',
        'animation',
        'animation_hidol',
        'hidol_patent'
    ];

    public static $team_it = [
        'interaction',
        'backend_docking',
        'h5',
        'tester',
        'operation'
    ];

    public static $team_quality = [
        'tester_quality',
        'operation_quality'
    ];

}