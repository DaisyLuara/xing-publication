<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Http\Controllers\Admin\Demand\V1\Models\DemandModify;
use App\Models\User;
use App\Notifications\DemandNotification;
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
        $notification_params = [
            'id' => $this->demandModify->getId(),
            'user_id' => $this->demandModify->getApplicantId(),
            'user_name' => $this->demandModify->applicant->name,
            'type' => 'demand_modify',
        ];

        $all_people = User::query()->permission("demand.modify.read")->get();
        $reviewers = User::query()->permission("demand.modify.review")->get(); //审核人
        /** @var DemandApplication $demand_application */
        $demand_application = $this->demandModify->demand_application;

        if ($this->type == 'create') {
            //创建通知指定接单人
            if ($demand_application->getReceiverId()) {
                $demand_application->receiver->notify(new DemandNotification(
                    array_merge($notification_params,
                        ['reply_content' => "新的需求修改创建：\n针对需求申请【" . $demand_application->getTitle() . '】有新的需求修改【' . $this->demandModify->getTitle() . '】创建,请及时处理']
                    )));
            }

        } else if ($this->type == 'un_review') {
            //未反馈时 通知 审核人
            if (!$this->demandModify->getHasFeedback() && $reviewers) {
                $notification_params['reply_content'] = "需求修改超时未反馈：\n 针对需求申请【" . $demand_application->getTitle() . '】的需求修改【' . $this->demandModify->getTitle() . '】接单人超时未反馈，请查看处理';
                Notification::send($reviewers, new DemandNotification($notification_params));
            }
        } else if ($this->type == 'update') {
            //更新通知指定接单人
            if ($demand_application->getReceiverId()) {
                $demand_application->receiver->notify(new DemandNotification(
                    array_merge($notification_params,
                        ['reply_content' => "新的需求修改更新：\n 针对需求申请【" . $demand_application->getTitle() . '】有新的需求修改【' . $this->demandModify->getTitle() . '】更新,请及时处理']
                    )));
            }

        } else if ($this->type == 'feedback') {
            //得到反馈 通知创建人与可审核人
            if ($this->demandModify->getHasFeedback()) {
                $notification_params['reply_content'] = "需求修改得到反馈：针对需求申请【" . $demand_application->getTitle() . '】的需求修改【' . $this->demandModify->getTitle() . '】已得到接单人的反馈';

                $this->demandModify->applicant->notify(new DemandNotification($notification_params));
                Notification::send($reviewers, new DemandNotification($notification_params));
            }
        } else if ($this->type == 'reviewed') {
            //审核通知创建人与接单人
            if ($this->demandModify->getStatus() == DemandModify::STATUS_PASS || $this->demandModify->getStatus() == DemandModify::STATUS_REJECT) {
                if ($this->demandModify->getStatus() == DemandModify::STATUS_PASS) {
                    $notification_params['reply_content'] = "需求修改已通过审核：针对需求申请【" . $demand_application->getTitle() . '】的需求修改【' . $this->demandModify->getTitle() . '】已通过' . $this->demandModify->getReviewerName() . '审核';
                } else if ($this->demandModify->getStatus() == DemandModify::STATUS_REJECT) {
                    $notification_params['reply_content'] = "需求修改审核不通过：针对需求申请【" . $demand_application->getTitle() . '】的需求修改【' . $this->demandModify->getTitle() . '】已通过' . $this->demandModify->getReviewerName()
                        . '审核不通过，不通过原因' . $this->demandModify->getRejectRemark();
                }
                $this->demandModify->applicant->notify(new DemandNotification($notification_params));
                $demand_application->receiver->notify(new DemandNotification($notification_params));
            }

        } else {
            $notification_params['reply_content'] = "需求修改【" . $this->demandModify->getTitle() . '】有新的变更，请及时查看';
            if ($all_people) {
                Notification::send($all_people, new DemandNotification($notification_params));
            }
        }
    }
}
