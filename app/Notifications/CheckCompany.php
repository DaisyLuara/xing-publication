<?php

namespace App\Notifications;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class CheckCompany extends Notification
{
    use Queueable;
    public $company;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
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

        $user = $this->company->user;

        // 存入数据库里的数据
        return [
            'id' => $this->company->id,
            'reply_content' => "新的公司被创建 \n公司名称：" . $this->company->name
                . " \n公司简称：" . $this->company->internal_name,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'wechat_notify' => true,
            'type' => 'review'
        ];
    }
}
