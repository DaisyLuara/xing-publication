<?php

namespace App\Notifications;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use App\Models\User;

class CheckReceipt extends Notification
{
    use Queueable;
    public $contract;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
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
        // 存入数据库里的数据
        return [
            'id' => $this->contract->id,
            'reply_content' => $this->contract->name.'收款日期截止',
            'user_id' =>  $this->contract->owner,
            'user_name' => User::find($this->contract->applicant,['name'])->name,
            'type' => 'review'
        ];

    }
}
