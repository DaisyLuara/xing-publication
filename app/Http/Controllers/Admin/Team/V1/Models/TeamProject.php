<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;

use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Models\Model;
use App\Models\User;

class TeamProject extends Model
{
    protected $fillable = [
        'project_name',
        'belong',
        'applicant',
        'project_attribute',
        'link_attribute',
        'h5_attribute',
        'xo_attribute',
        'begin_date',
        'online_date',
        'remark',
        'status',
    ];

    public function member()
    {
        return $this->belongsToMany(User::class, 'team_project_members', 'team_project_id', 'user_id')->withPivot(['user_name', 'type', 'rate']);
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }

    public function project()
    {
        return $this->setConnection('ar')->belongsTo(Project::class, 'belong', 'versionname');
    }
}
