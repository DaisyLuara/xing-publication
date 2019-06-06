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
        $contract = $this->contract;
        return [
            'id' => $contract->id,
            'reply_content' => $contract->name . '收款日期截止' . "\r\n" . '公司名称:' . $contract->company->name . "\r\n" . '负责人:' . $contract->ownerUser->name,
            'user_id' => $contract->owner,
            'user_name' => User::find($contract->owner, ['name'])->name,
            'type' => 'review'
        ];

    }
}
