<?php

namespace App\Http\Controllers\Admin\Demand\V1\Models;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\Model;
use App\Models\User;
use Illuminate\Notifications\Notifiable;

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
