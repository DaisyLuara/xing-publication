<?php

namespace App\Http\Controllers\Admin\Demand\V1\Models;

use App\Models\Model;
use App\Models\User;
use Illuminate\Notifications\Notifiable;

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
