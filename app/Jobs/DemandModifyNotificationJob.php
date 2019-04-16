<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Http\Controllers\Admin\Demand\V1\Models\DemandModify;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class DemandModifyNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var DemandModify $demandModify */
    public $demandModify;
    public $type;

    /**
     * Create a new job instance.
     * DemandApplicationNotificationJob constructor.
     * @param DemandModify $demandModify
     * @param string $type
     */
    public function __construct(DemandModify $demandModify, string $type)
    {
        $this->demandModify = $demandModify;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var DemandApplication $demand_application */
        $demand_application = $this->demandModify->demand_application;

        $str = ""
            . "  \n项目标的：" . $demand_application->getTitle()
            . "  \n修改标题：" . $this->demandModify->getTitle()
            . "  \n修改创建人：" . ($this->demandModify->applicant ? $this->demandModify->applicant->name : " -- ")
            . "  \n项目标的接单人：" . ($demand_application->receiver ? $demand_application->receiver->name : " -- ")
            . "  \n修改创建时间：" . $this->demandModify->getCreatedAt()
            . "  \n反馈结果：" . ($this->demandModify->getHasFeedback() ? ("已反馈," . $this->demandModify->getFeedback()) : "未反馈")
            . "  \n审核结果：" . $this->demandModify->getStatusText();

        if ($this->demandModify->getStatus() != DemandModify::STATUS_UN_REVIEW) {
            $str .= "  \n审核人：" . $this->demandModify->getReviewerName();
        }

        if ($this->demandModify->getStatus() == DemandModify::STATUS_REJECT) {
            $str .= "  \n驳回原因：" . $this->demandModify->getRejectRemark();
        }

        $notification_params = [
            'id' => $this->demandModify->getId(),
            'user_id' => $this->demandModify->getApplicantId(),
            'user_name' => $this->demandModify->applicant->name,
            'type' => 'demand_modify',
            'reply_content' => "需求修改有新的变更，请及时查看!" . $str
        ];

        $all_people = User::query()->permission("demand.modify.read")->get();
        $reviewers = User::query()->permission("demand.modify.review")->get(); //审核人：法务主管，绩效主管，平台运营


        if ($this->type == 'create' || $this->type == 'update') {
            //创建更新 通知指定接单人
            if ($demand_application->getReceiverId()) {
                $type_text = $this->type == 'create' ? "创建" : "更新";
                $demand_application->receiver->notify(new BaseNotification(
                    array_merge($notification_params,
                        ['reply_content' => "有新的需求修改" . $type_text . ",请及时处理!" . $str]
                    )));
            }
        } else if ($this->type == 'un_review') {
            //未反馈时 通知 审核人
            if (!$this->demandModify->getHasFeedback() && $reviewers) {
                $notification_params['reply_content'] = "需求修改接单人超时未反馈，请查看处理!" . $str;
                Notification::send($reviewers, new BaseNotification($notification_params));
            }
        } else if ($this->type == 'feedback') {
            //得到反馈 通知创建人与可审核人(法务主管，绩效主管，平台运营
            if ($this->demandModify->getHasFeedback()) {
                $notification_params['reply_content'] = "需求修改已得到接单人的反馈!" . $str;
                $this->demandModify->applicant->notify(new BaseNotification($notification_params));

                if ($reviewers) {
                    Notification::send($reviewers, new BaseNotification($notification_params));
                }
            }
        } else if ($this->type == 'reviewed') {
            //审核通知创建人与接单人  && 审核人：法务主管，绩效主管，平台运营
            if ($this->demandModify->getStatus() == DemandModify::STATUS_PASS || $this->demandModify->getStatus() == DemandModify::STATUS_REJECT) {

                if ($this->demandModify->getStatus() == DemandModify::STATUS_PASS) {
                    $notification_params['reply_content'] = "需求修改审核通过!" . $str;
                } else if ($this->demandModify->getStatus() == DemandModify::STATUS_REJECT) {
                    $notification_params['reply_content'] = "需求修改审核不通过!" . $str;
                }

                $this->demandModify->applicant->notify(new BaseNotification($notification_params));
                $demand_application->receiver->notify(new BaseNotification($notification_params));
                if ($reviewers) {
                    Notification::send($reviewers, new BaseNotification($notification_params));
                }
            }
        } else {
            if ($all_people) {
                Notification::send($all_people, new BaseNotification($notification_params));
            }
        }
    }
}
