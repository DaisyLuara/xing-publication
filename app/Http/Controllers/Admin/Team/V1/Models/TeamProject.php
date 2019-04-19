<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;
use App\Models\User;

/**
 * App\Http\Controllers\Admin\Team\V1\Models\TeamProject
 *
 * @property int $id
 * @property int $copyright_attribute 是否为原创 0:原创节目 1:非原创节目
 * @property int|null $copyright_project_id 原创节目
 * @property string $project_name 节目名称
 * @property string|null $belong 节目
 * @property int $applicant 申请人
 * @property string $project_attribute 0不计入 1基础条目 2简单条目 3通用节目 4项目
 * @property int $hidol_attribute Hidol属性：0 否 1 是
 * @property int $individual_attribute 定制属性：0 非定制 1 定制特别节目 2 定制普通节目
 * @property int|null $contract_id 定制合同ID
 * @property string $interaction_attribute 交互技术属性：interaction_api中间件属性,interaction_linkage联动引擎属性(多个以都=逗号隔开)
 * @property int $link_attribute 联动属性 1:是,0:否
 * @property int $h5_attribute 1:基础模版,2:复杂模版
 * @property int|null $xo_attribute 小偶属性 1:是,0:否
 * @property string|null $begin_date 开始时间
 * @property string|null $online_date 上线时间
 * @property string|null $launch_date 投放时间
 * @property string|null $art_innovate 艺术风格创新点
 * @property string|null $dynamic_innovate 动效体验创新点
 * @property string|null $interact_innovate 交互技术创新点
 * @property string|null $remark 项目说明
 * @property int $status 1:进行中,2:测试确认,3:运营确认,4:主管确认
 * @property int $type 节目类型:1:提前,0:正常
 * @property string|null $media_id 压缩包地址
 * @property int|null $operation_media_id 运营文档资料
 * @property int|null $tester_media_id 测试文档资料
 * @property int|null $animation_media_id 设计动画素材
 * @property string|null $plan_media_id [节目交互]文档,多选，逗号隔开
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $test_remark 测试备注
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media|null $animation_media
 * @property-read \App\Models\User $applicantUser
 * @property-read \App\Http\Controllers\Admin\Contract\V1\Models\Contract|null $contract
 * @property-read \App\Http\Controllers\Admin\Team\V1\Models\TeamProject|null $copyright_project
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media|null $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $member
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media|null $operation_media
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media|null $tester_media
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereAnimationMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereArtInnovate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereBeginDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereCopyrightAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereCopyrightProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereDynamicInnovate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereH5Attribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereHidolAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereIndividualAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereInteractInnovate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereInteractionAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereLaunchDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereLinkAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereOnlineDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereOperationMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject wherePlanMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereProjectAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereTestRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereTesterMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamProject whereXoAttribute($value)
 * @mixin \Eloquent
 */
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
        '0' => '不计入',
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
