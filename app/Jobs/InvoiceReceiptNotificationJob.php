<?php

namespace App\Jobs;

use EasyWeChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceReceiptNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $content)
    {
        $this->user = $user;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $content = $this->content;
        $officialAccount = EasyWeChat::officialAccount();
        $message = [
            'touser' => $user->weixin_openid,
            'template_id' => config('wechat.official_account_template_id.notification'),
            'url' => null,
            'data' => [
                'first' => $user->name . " 您有一条新的通知消息",
                'keyword1' => $content,
                'keyword2' => ' -- ',
                'keyword3' => ' -- ',
                'keyword4' => ' --',
                'keyword5' => '数据中台',
                'remark' => '',
            ]
        ];
        $officialAccount->template_message->send($message);
    }

}
