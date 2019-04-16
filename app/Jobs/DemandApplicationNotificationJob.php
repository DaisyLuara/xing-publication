<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Models\User;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class DemandApplicationNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var DemandApplication DemandApplication */
    public $demandApplication;
    public $type;

    /**
     * Create a new job instance.
     * DemandApplicationNotificationJob constructor.
     * @param DemandApplication $demandApplication
     * @param string $type
     */
    public function __construct(DemandApplication $demandApplication, string $type)
    {
        $this->demandApplication = $demandApplication;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var User $demandApplication */
        $demandApplicant = $this->demandApplication->applicant;
        $str = ""
            . "  \n项目标的：" . $this->demandApplication->getTitle()
            . "  \n申请状态：" . $this->demandApplication->getStatusText()
            . "  \n申请人：" . $demandApplicant->name
            . "  \n申请时间：" . $this->demandApplication->getCreatedAt()
            . "  \n接单人：" . ($this->demandApplication->receiver ? $this->demandApplication->receiver->name : " -- ");

        $notification_params = [
            'id' => $this->demandApplication->getId(),
            'user_id' => $this->demandApplication->getApplicantId(),
            'user_name' => $demandApplicant->name,
            'type' => 'demand_application',
            'reply_content' => "需求申请有新的变更!" . $str
        ];


        $all_people = User::query()->permission("demand.application.read")->get();
        $receivers = User::query()->permission("demand.application.receive")->get(); //接单人 - 所有产品经理、所有设计
        $receiver_specials = User::query()->permission("demand.application.receive_special")->get(); // 法务主管
        $operation = User::query()->role('operation')->get(); // 平台运营

        if ($this->type == 'create' || $this->type == 'update' || $this->type == 'confirm') {

            //  通知人：所有产品经理、所有设计、平台运营、BD对应的BD主管
            if ($this->type == 'create') {
                $notification_params['reply_content'] = "新的需求申请被创建,等待接单!" . $str;
            } else if ($this->type == 'update') {
                $notification_params['reply_content'] = "需求申请被修改,等待接单!" . $str;
            } else if ($this->type == 'confirm') {
                $notification_params['reply_content'] = "需求申请已由" . $this->demandApplication->getConfirmName() . "确认完成!" . $str;
            }

            //创建通知可接单人 所有产品经理、所有设计
            if ($receivers) {
                Notification::send($receivers, new BaseNotification($notification_params));
            }

            //创建人是否有上级
            $demandApplicationParent = User::query()->find($demandApplicant->parent_id);
            if ($demandApplicationParent) {
                Notification::send($demandApplicationParent, new BaseNotification($notification_params));
            }

            //平台运营
            if ($operation) {
                Notification::send($operation, new BaseNotification($notification_params));
            }

        } else if ($this->type == 'un_receive') {
            //未接单时通知特殊接单人
            if ($this->demandApplication->getStatus() == DemandApplication::STATUS_UN_RECEIVE) {
                $notification_params['reply_content'] = "需求申请超时无人接单，等待指派接单!" . $str;

                if ($receiver_specials) {
                    Notification::send($receiver_specials, new BaseNotification($notification_params));
                }
            }
        } else if ($this->type == 'received') {
            //被接单时,通知已接单人与创建人
            if ($this->demandApplication->getStatus() == DemandApplication::STATUS_RECEIVED
                && $this->demandApplication->getApplicantId() && $this->demandApplication->getReceiverId()
            ) {
                $this->demandApplication->applicant->notify(new BaseNotification(
                    array_merge($notification_params,
                        ['reply_content' => "需求申请已由" . $this->demandApplication->getReceiverName() . "接单!" . $str]
                    )));
                $this->demandApplication->receiver->notify(new BaseNotification(
                    array_merge($notification_params,
                        ['reply_content' => "您已接单需求申请!" . $str]
                    )));
            }

        } else {
            if ($all_people) {
                Notification::send($all_people, new BaseNotification($notification_params));
            }
        }
    }
}
