<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Models\User;
use App\Notifications\DemandNotification;
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
        $notification_params = [
            'id' => $this->demandApplication->getId(),
            'user_id' => $this->demandApplication->getApplicantId(),
            'user_name' => $demandApplicant->name,
            'type' => 'demand_application',
            'reply_content' => "需求申请【" . $this->demandApplication->getTitle() . '】有新的变更'
        ];

        $all_people = User::query()->permission("demand.application.read")->get();
        $receivers = User::query()->permission("demand.application.receive")->get(); //接单人 - 所有产品经理、所有设计
        $receiver_specials = User::query()->permission("demand.application.receive_special")->get(); // 法务主管
        $operation = User::query()->role('operation')->get(); // 平台运营

        if ($this->type == 'create' || $this->type == 'update' || $this->type == 'confirm') {

            //  通知人：所有产品经理、所有设计、平台运营、BD对应的BD主管
            if($this->type == 'create'){
                $notification_params['reply_content'] = "新的需求申请 -- 【" . $this->demandApplication->getTitle() . '】被创建,等待接单';
            }else if($this->type == 'update'){
                $notification_params['reply_content'] = "需求申请更新 -- 【" . $this->demandApplication->getTitle() . '】被修改,等待接单';
            }else if($this->type == 'confirm'){
                $notification_params['reply_content'] = "需求申请已确认完成 --【" . $this->demandApplication->getTitle() . '】已由' . $this->demandApplication->getConfirmName() . '确认完成';
            }

            //创建通知可接单人 所有产品经理、所有设计
            if ($receivers) {
                Notification::send($receivers, new DemandNotification($notification_params));
            }

            //创建人是否有上级
            $demandApplicationParent = User::query()->find($demandApplicant->parent_id);
            if($demandApplicationParent){
                Notification::send($demandApplicationParent, new DemandNotification($notification_params));
            }

            //平台运营
            if ($operation) {
                Notification::send($operation, new DemandNotification($notification_params));
            }

        } else if ($this->type == 'un_receive') {
            //未接单时通知特殊接单人
            if ($this->demandApplication->getStatus() == DemandApplication::STATUS_UN_RECEIVE) {
                $notification_params['reply_content'] = "需求申请无人接单 --  【" . $this->demandApplication->getTitle() . '】超时无人接单，等待指派接单';

                if ($receiver_specials) {
                    Notification::send($receiver_specials, new DemandNotification($notification_params));
                }
            }
        } else if ($this->type == 'received') {
            //被接单时,通知已接单人与创建人
            if ($this->demandApplication->getStatus() == DemandApplication::STATUS_RECEIVED
                && $this->demandApplication->getApplicantId() && $this->demandApplication->getReceiverId()
            ) {
                $this->demandApplication->applicant->notify(new DemandNotification(
                    array_merge($notification_params,
                        ['reply_content' => "需求申请已接单 -- 您的需求申请【" . $this->demandApplication->getTitle() . '】已被' . $this->demandApplication->getReceiverName() . '接单']
                    )));
                $this->demandApplication->receiver->notify(new DemandNotification(
                    array_merge($notification_params,
                        ['reply_content' => "需求申请已接单 -- 您已接单需求申请【" . $this->demandApplication->getTitle() . '】']
                    )));
            }

        } else {
            if ($all_people) {
                Notification::send($all_people, new DemandNotification($notification_params));
            }
        }
    }
}
