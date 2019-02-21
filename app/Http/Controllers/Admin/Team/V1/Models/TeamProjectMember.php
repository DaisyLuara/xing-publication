<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;

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