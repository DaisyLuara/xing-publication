<?php

namespace App\Http\Controllers\Admin\Demand\V1\Request;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;


class DemandModifyRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {
            case 'POST':
                return [
                    'demand_application_id' => ['required',
                        Rule::exists('demand_applications', 'id')->where(function ($query) {
                            $query->whereIn('status', [DemandApplication::STATUS_RECEIVED, DemandApplication::STATUS_MODIFY]);
                        })
                    ],
                    'title' => ["required", "string"],
                    'content' => ["required", "string"],
                ];
                break;

            case 'PATCH':
                return [
                    'demand_application_id' => ['required',
                        Rule::exists('demand_applications', 'id')->where(function ($query) {
                            $query->whereIn('status', [DemandApplication::STATUS_RECEIVED, DemandApplication::STATUS_MODIFY]);
                        })
                    ],
                    'title' => ["required", "string"],
                    'content' => ["required", "string"],
                ];
                break;

            default:
                return [];
        }
    }

    public function attributes()
    {
        return [
            'demand_application_id' => '项目标的',
            'applicant_id' => '申请人',
            'title' => '需求修改标题',
            'content' => '需求修改内容',
            'has_feedback' => '是否存在反馈',
            'feedback' => '反馈内容',
            'feedback_time' => '反馈时间',
            'feedback_person_id' => '反馈人ID',
            'feedback_person_name' => '反馈人姓名',
            'status' => '状态',
            'reviewer_id' => '审核人ID',
            'reviewer_name' => '审核人姓名',
            'review_time' => '审核时间',
            'reject_remark' => '审核备注',
        ];
    }

}
