<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;
use App\Models\User;

class TeamPersonFutureReward extends Model
{
    protected $fillable = [
        'user_id',
        'project_name',
        'belong',
        'experience_money',
        'xo_money',
        'link_money',
        'system_money',
        'total',
        'date',
        'type',
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

