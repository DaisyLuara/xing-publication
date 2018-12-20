<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;
use App\Models\User;

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