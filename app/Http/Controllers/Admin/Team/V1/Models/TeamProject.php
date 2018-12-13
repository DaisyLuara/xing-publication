<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
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
        'launch_date',
        'art_innovate',
        'dynamic_innovate',
        'interact_innovate',
        'remark',
        'status',
        'type',
        'media_id'
    ];

    public function member()
    {
        return $this->belongsToMany(User::class, 'team_project_members', 'team_project_id', 'user_id')->withPivot(['user_name', 'type', 'rate']);
    }

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }

}
