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
        $notification_params = [
            'id' => $this->demandApplication->getId(),
            'user_id' => $this->demandApplication->getApplicantId(),
            'user_name' => $this->demandApplication->applicant->name,
            'type' => 'demand_application',
        ];

        $all_people = User::query()->permission("demand.application.read")->get();
        $receivers = User::query()->permission("demand.application.receive")->get();
        $receiver_specials = User::query()->permission("demand.application.receive_special")->get();

        if ($this->type == 'create') {
            //创建通知可接单人
            $notification_params['reply_content'] = "新的需求申请:\n 有新的需求申请【" . $this->demandApplication->getTitle() . '】被创建,请查看是否接单';

            if ($receivers) {
                Notification::send($receivers, new DemandNotification($notification_params));
            }

        } else if ($this->type == 'update') {
            //更新通知可接单人
            $notification_params['reply_content'] = "需求申请更新:\n 需求申请【" . $this->demandApplication->getTitle() . '】被修改,请查看是否接单';

            if ($receivers) {
                Notification::send($receivers, new DemandNotification($notification_params));
            }

        } else if ($this->type == 'un_receive') {
            //未接单时通知特殊接单人
            if ($this->demandApplication->getStatus() == DemandApplication::STATUS_UN_RECEIVE) {
                $notification_params['reply_content'] = "需求申请无人接单:\n 有新的需求申请【" . $this->demandApplication->getTitle() . '】超时无人接单，请及时处理';

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
                        ['reply_content' => "需求申请已接单:\n 您的需求申请【" . $this->demandApplication->getTitle() . '】已被' . $this->demandApplication->getReceiverName() . '接单']
                    )));
                $this->demandApplication->receiver->notify(new DemandNotification(
                    array_merge($notification_params,
                        ['reply_content' => "需求申请已接单:\n 您已接单需求申请【" . $this->demandApplication->getTitle() . '】']
                    )));
            }

        } else if ($this->type == 'confirm') {
            $notification_params['reply_content'] = "需求申请已确认完成:\n 需求申请【" . $this->demandApplication->getTitle() . '】已由' . $this->demandApplication->getConfirmName() . '确认完成';
            if ($all_people) {
                Notification::send($all_people, new DemandNotification($notification_params));
            }

        } else {
            $notification_params['reply_content'] = "需求申请【" . $this->demandApplication->getTitle() . '】有新的变更，请及时查看';
            if ($all_people) {
                Notification::send($all_people, new DemandNotification($notification_params));
            }
        }
    }
}
