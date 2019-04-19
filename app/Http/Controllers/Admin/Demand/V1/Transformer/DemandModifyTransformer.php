<?php

namespace App\Http\Controllers\Admin\Demand\V1\Transformer;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandModify;
use League\Fractal\TransformerAbstract;

class DemandModifyTransformer extends TransformerAbstract
{

    protected $defaultIncludes = ['demand_application'];

    public function transform(DemandModify $demandModify): array
    {

        return [
            'id' => $demandModify->getId(),
            'demand_application_id' => $demandModify->getDemandApplicationId(),
            'applicant_id' => $demandModify->getApplicantId(),
            'applicant_name' => $demandModify->applicant->name,
            'title' => $demandModify->getTitle(),
            'content' => $demandModify->getContent(),
            'has_feedback' => $demandModify->getHasFeedback(),
            'feedback' => $demandModify->getFeedback(),
            'feedback_time' => (string)$demandModify->getFeedbackTime(),
            'feedback_person_id' => $demandModify->getFeedbackPersonId(),
            'feedback_person_name' => $demandModify->getFeedbackPersonName(),
            'status' => $demandModify->getStatus(),
            'status_text' => $demandModify->getStatusText(),
            'reviewer_id' => $demandModify->getReviewerId(),
            'reviewer_name' => $demandModify->getReviewerName(),
            'review_time' => (string)$demandModify->getReviewTime(),
            'reject_remark' => $demandModify->getRejectRemark(),
            'created_at' => (string)$demandModify->getCreatedAt(),
            'updated_at' => (string)$demandModify->getUpdatedAt(),
        ];
    }

    public function includeDemandApplication(DemandModify $demandModify)
    {
        $demandApplication = $demandModify->demand_application;
        if ($demandApplication) {
            return $this->item($demandApplication, new DemandApplicationTransformer());
        }
        return null;
    }

}