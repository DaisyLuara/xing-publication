<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\AdminProject;

class CheckAdminProject extends Notification
{
    use Queueable;

    public $adminProject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AdminProject $adminProject)
    {
        $this->adminProject = $adminProject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {

        $user = $this->adminProject->user;

        return [
            'company_id' => $this->adminProject->id,
            'reply_content' => '节目创建待审批',
            'user_id' => $user->id,
            'user_name' => $user->name,
        ];

    }
}
