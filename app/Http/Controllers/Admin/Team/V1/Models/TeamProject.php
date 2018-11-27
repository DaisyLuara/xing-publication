<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;

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
        'start_date',
        'online_date',
        'launch_date',
        'remark',
        'status'
    ];

    public function member()
    {
        return $this->belongsToMany(User::class, 'team_project_members', 'team_project_id', 'user_id')->withPivot(['type','rate']);
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }
}
