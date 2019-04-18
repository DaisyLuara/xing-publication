<?php

namespace App\Http\Controllers\Admin\Demand\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;
use Illuminate\Notifications\Notifiable;

/**
 * App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication
 *
 * @property int $id
 * @property string $title 项目标的(唯一)
 * @property int $applicant_id 申请人ID
 * @property string|null $launch_point_remark 投放地点备注
 * @property int $has_contract 是否有合同
 * @property int $project_num 节目数量
 * @property string|null $similar_project_name 类似节目
 * @property string $expect_online_time 期望上线时间
 * @property string $expect_receiver_ids 期望接单人ID(逗号隔开)
 * @property string|null $big_screen_demand 大屏节目需求
 * @property string|null $h5_demand H5节目需求
 * @property string|null $other_demand 其他定制内容
 * @property string|null $applicant_remark 申请人备注
 * @property int $status 0:未接单 1:已完成 2:已接单 3:修改中
 * @property int|null $receiver_id 接单人ID
 * @property string|null $receiver_name 接单人名称
 * @property string|null $receiver_remark 接单人备注
 * @property string|null $receiver_time 接单人备注
 * @property int|null $confirm_id 确认完成人
 * @property string|null $confirm_name 确认完成人名称
 * @property string|null $confirm_time 确认完成时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $applicant
 * @property-read \App\Models\User|null $confirm_person
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Contract\V1\Models\Contract[] $contracts
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\User|null $receiver
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereApplicantRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereBigScreenDemand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereConfirmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereConfirmName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereConfirmTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereExpectOnlineTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereExpectReceiverIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereH5Demand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereHasContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereLaunchPointRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereOtherDemand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereProjectNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereReceiverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereReceiverRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereReceiverTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereSimilarProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DemandApplication extends Model
{

    use Notifiable;

    protected $fillable = [
        'title',
        'applicant_id',
        'launch_point_remark',
        'has_contract',
        'project_num',
        'similar_project_name',
        'expect_online_time',
        'expect_receiver_ids',
        'big_screen_demand',
        'h5_demand',
        'other_demand',
        'applicant_remark',
        'status',
        'receiver_id',
        'receiver_name',
        'receiver_remark',
        'receiver_time',
        'confirm_id',
        'confirm_name',
        'confirm_time',
    ];


    const STATUS_UN_RECEIVE = 0;
    const STATUS_CONFIRM = 1;
    const STATUS_RECEIVED = 2;
    const STATUS_MODIFY = 3;

    public static $statusAttributeMapping = [
        self::STATUS_UN_RECEIVE => "未接单",
        self::STATUS_CONFIRM => "已完成",
        self::STATUS_RECEIVED => "已接单",
        self::STATUS_MODIFY => "修改中",
    ];

    public function getStatusText(){
        return self::$statusAttributeMapping[$this->getStatus()]??'未知';
    }

    public function applicant(){
        return $this->belongsTo(User::class,'applicant_id','id');
    }

    public function contracts(){
        return $this->morphToMany(Contract::class, 'association','contract_relationships');
    }

    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id','id');
    }

    public function confirm_person(){
        return $this->belongsTo(User::class,'confirm_id','id');
    }




    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getApplicantId()
    {
        return $this->applicant_id;
    }

    /**
     * @return mixed
     */
    public function getLaunchPointRemark()
    {
        return $this->launch_point_remark;
    }

    /**
     * @return mixed
     */
    public function getHasContract()
    {
        return $this->has_contract;
    }

    /**
     * @return mixed
     */
    public function getProjectNum()
    {
        return $this->project_num;
    }

    /**
     * @return mixed
     */
    public function getSimilarProjectName()
    {
        return $this->similar_project_name;
    }

    /**
     * @return mixed
     */
    public function getExpectOnlineTime()
    {
        return $this->expect_online_time;
    }

    /**
     * @return mixed
     */
    public function getExpectReceiverIds()
    {
        return $this->expect_receiver_ids;
    }

    /**
     * @return mixed
     */
    public function getBigScreenDemand()
    {
        return $this->big_screen_demand;
    }

    /**
     * @return mixed
     */
    public function getH5Demand()
    {
        return $this->h5_demand;
    }

    /**
     * @return mixed
     */
    public function getOtherDemand()
    {
        return $this->other_demand;
    }

    /**
     * @return mixed
     */
    public function getApplicantRemark()
    {
        return $this->applicant_remark;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getReceiverId()
    {
        return $this->receiver_id;
    }

    /**
     * @return mixed
     */
    public function getReceiverName()
    {
        return $this->receiver_name;
    }

    /**
     * @return mixed
     */
    public function getReceiverRemark()
    {
        return $this->receiver_remark;
    }

    /**
     * @return mixed
     */
    public function getReceiverTime()
    {
        return $this->receiver_time;
    }

    /**
     * @return mixed
     */
    public function getConfirmId()
    {
        return $this->confirm_id;
    }

    /**
     * @return mixed
     */
    public function getConfirmName()
    {
        return $this->confirm_name;
    }

    /**
     * @return mixed
     */
    public function getConfirmTime()
    {
        return $this->confirm_time;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }



}
