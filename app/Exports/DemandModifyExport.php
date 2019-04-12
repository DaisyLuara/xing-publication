<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandModify;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DemandModifyExport extends BaseExport
{
    private $demand_application_id;//需求申请ID
    private $has_feedback;//反馈状态
    private $status;//平台意见
    private $create_start_date, $create_end_date; //开始日期,结束日期


    public function __construct($request)
    {
        $this->create_start_date = $request->create_start_date;
        $this->create_end_date = $request->create_end_date;
        $this->status = $request->status;
        $this->demand_application_id = $request->demand_application_id;
        $this->has_feedback = $request->has_feedback;

        $this->fileName = '需求-需求修改列表';
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public function collection()
    {
        $query = DemandModify::query();
        /** @var User $user */
        $user = Auth::user();

        //需求申请
        if ($this->demand_application_id) {
            $query->where("demand_application_id", "=", $this->demand_application_id);
        }

        //反馈意见
        if ($this->has_feedback) {
            $query->where("has_feedback", "=", $this->has_feedback);
        }

        //平台意见
        if ($this->status) {
            $query->where("status", "=", $this->status);
        }

        //创建时间
        if ($this->create_start_date && $this->create_end_date) {
            $query->whereRaw("date_format(created_at, '%Y-%m-%d') between '$this->create_start_date' and '$this->create_end_date'");
        }

        if (!$user->hasAnyPermission(['demand.modify.review'])) {

            $query->whereHas('demand_application', function ($demand_application) use ($user) {
                //接单人看到自己接单的
                if ($user->hasAnyPermission(['demand.modify.feedback'])) {
                    $demand_application->where("receiver_id", '=', $user->id);
                } else {
                    if ($user->hasRole("bd-manager")) {
                        //BD主管可查看自己及下属BD新建的申请列表
                        $user_ids = $user->subordinates()->pluck("id")->toArray();
                        $user_ids[] = $user->id;
                        $demand_application->whereIn('applicant_id', $user_ids);
                    } else if ($user->hasRole("user") || $user->hasRole("business-operation")) {
                        //只能查询自己创建的 Application
                        $demand_application->where('applicant_id', '=', $user->id);
                    }
                }

            });

        }

        $demandModifies = $query->orderByDesc("id")->get()
            ->map(function (DemandModify $demandModify) {
                return [
                    'id' => $demandModify->getId(),
                    'demand_application_title' => $demandModify->demand_application ? $demandModify->demand_application->title : '',
                    'applicant_name' => $demandModify->applicant ? $demandModify->applicant->name : '',
                    'title' => $demandModify->getTitle(),
                    'content' => $demandModify->getContent(),
                    'has_feedback' => $demandModify->getHasFeedback() ? '已反馈' : '未反馈',
                    'feedback_person_name' => $demandModify->getFeedbackPersonName(),
                    'feedback' => $demandModify->getFeedback(),
                    'feedback_time' => (string)$demandModify->getFeedbackTime(),
                    'reviewer_name' => $demandModify->getReviewerName(),
                    'reject_remark' => $demandModify->getRejectRemark(),
                    'review_time' => (string)$demandModify->getReviewTime(),
                    'status_text' => $demandModify->getStatusText(),
                    'created_at' => (string)$demandModify->getCreatedAt(),
                    'updated_at' => (string)$demandModify->getUpdatedAt(),
                ];
            })->toArray();


        $header = ['ID', '项目标的', '申请人', '修改标题', '修改详情', '是否有反馈', '反馈人', '反馈内容', '反馈时间',
            '审核人', '审核不通过备注', '审核时间', '状态', '创建时间', '最新更新时间'];


        $this->header_num = count($header);
        array_unshift($demandModifies, $header, $header);
        $this->data = $data = collect($demandModifies);

        return $data;
    }


}