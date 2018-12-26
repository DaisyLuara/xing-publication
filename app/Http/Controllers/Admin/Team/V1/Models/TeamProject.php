<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;
use App\Models\User;

class TeamProject extends Model
{
    protected $fillable = [
        'project_name',
        'belong',
        'applicant',
        'project_attribute',
        'hidol_attribute',
        'individual_attribute',
        'contract_id',
        'interaction_attribute',
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
        'media_id',
        'operation_media_id',
        'tester_media_id',
        'animation_media_id',
//        'plan_media_id',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

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

    public function operation_media()
    {
        return $this->belongsTo(Media::class, 'operation_media_id', 'id');
    }

    public function tester_media()
    {
        return $this->belongsTo(Media::class, 'tester_media_id', 'id');
    }

    public function animation_media()
    {
        return $this->belongsTo(Media::class, 'animation_media_id', 'id');
    }


}
