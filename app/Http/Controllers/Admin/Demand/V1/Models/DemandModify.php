<?php

namespace App\Http\Controllers\Admin\Demand\V1\Models;

use App\Models\Model;
use App\Models\User;
use Illuminate\Notifications\Notifiable;

/**
 * App\Http\Controllers\Admin\Demand\V1\Models\DemandModify
 *
 * @property int $id
 * @property int $demand_application_id 需求申请ID
 * @property int $applicant_id 申请人ID
 * @property string $title 需求修改标题
 * @property string $content 需求修改详情
 * @property int $has_feedback 是否反馈
 * @property string|null $feedback 反馈内容
 * @property string|null $feedback_time 反馈时间
 * @property int|null $feedback_person_id 反馈人ID
 * @property string|null $feedback_person_name 反馈人ID
 * @property int $status 0:待处理 1:已审核 2：已驳回
 * @property int|null $reviewer_id 审核人ID
 * @property string|null $reviewer_name 审核人名称
 * @property int|null $review_time 审核时间
 * @property string|null $reject_remark 驳回备注
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $applicant
 * @property-read \App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication $demand_application
 * @property-read \App\Models\User|null $feedback_person
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\User|null $reviewer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereDemandApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereFeedbackPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereFeedbackPersonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereFeedbackTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereHasFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereRejectRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereReviewTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereReviewerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereReviewerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Demand\V1\Models\DemandModify whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DemandModify extends Model
{
    use Notifiable;

    protected $fillable = [
        'demand_application_id',
        'applicant_id',
        'title',
        'content',
        'has_feedback',
        'feedback',
        'feedback_time',
        'feedback_person_id',
        'feedback_person_name',
        'status',
        'reviewer_id',
        'reviewer_name',
        'review_time',
        'reject_remark',
    ];

    const STATUS_UN_REVIEW = 0;
    const STATUS_PASS = 1;
    const STATUS_REJECT = 2;

    public static $statusAttributeMapping = [
        self::STATUS_UN_REVIEW => "待处理",
        self::STATUS_PASS => "已审核",
        self::STATUS_REJECT => "已驳回",
    ];

    public function getStatusText()
    {
        return self::$statusAttributeMapping[$this->getStatus()] ?? '未知';
    }

    public function demand_application()
    {
        return $this->belongsTo(DemandApplication::class, 'demand_application_id', 'id');
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id', 'id');
    }

    public function feedback_person()
    {
        return $this->belongsTo(User::class, 'feedback_person_id', 'id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'id');
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
    public function getDemandApplicationId()
    {
        return $this->demand_application_id;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getHasFeedback()
    {
        return $this->has_feedback;
    }

    /**
     * @return mixed
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * @return mixed
     */
    public function getFeedbackTime()
    {
        return $this->feedback_time;
    }

    /**
     * @return mixed
     */
    public function getFeedbackPersonId()
    {
        return $this->feedback_person_id;
    }

    /**
     * @return mixed
     */
    public function getFeedbackPersonName()
    {
        return $this->feedback_person_name;
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
    public function getReviewerId()
    {
        return $this->reviewer_id;
    }

    /**
     * @return mixed
     */
    public function getReviewerName()
    {
        return $this->reviewer_name;
    }

    /**
     * @return mixed
     */
    public function getReviewTime()
    {
        return $this->review_time;
    }

    /**
     * @return mixed
     */
    public function getRejectRemark()
    {
        return $this->reject_remark;
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
