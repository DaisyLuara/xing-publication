<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DemandManagement extends Notification
{
    use Queueable;
    public $contract;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // 存入数据库里的数据
        return [
            'id' => $this->contract->id,
            'reply_content' => $this->contract->name.'收款日期截止',
            'user_id' =>  $this->contract->applicant,
            'user_name' => User::find($this->contract->applicant,['name'])->name,
            'type' => 'review'
        ];
    }
}
