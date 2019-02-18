<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;
use App\Models\User;

class TeamProject extends Model
{
    protected $fillable = [
        'copyright_attribute',
        'copyright_project_id',
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
        'test_remark',
//        'plan_media_id',
    ];

    public static $projectAttributeMapping = [
        '0' => '不计入',
        '1' => '基础条目',
        '2' => '简单条目',
        '3' => '节目',
        '4' => '更多',
    ];
    public static  $h5AttributeMapping = [
        '1' => '基础模版',
        '2' => '复杂模版',
    ];

    public static  $statusMapping = [
        '1' => '进行中',
        '2' => '测试已确认',
        '3' => '运营已确认',
        '4' => '主管已确认'
    ];
    public static  $interactionAttributeMapping = [
        'interaction_api' => '中间件属性',
        'interaction_linkage' => '联动引擎属性'
    ];

    public static  $individualAttributeMapping = [
        0 => '非定制节目',
        1 => '定制特别节目',
        2 => '定制通用节目',
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

    public function copyright_project()
    {
        return $this->belongsTo(TeamProject::class, 'copyright_project_id', 'id');
    }


}
