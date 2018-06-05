<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use EasyWeChat;

class WeekRankingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $openId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $openId)
    {
        $this->data = $data;
        $this->openId = $openId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $officialAccount = EasyWeChat::officialAccount();
        $message = [
            'touser' => $this->openId,
            'template_id' => 'tEaeatGQCZ7tanD4JuFIoddvw8dgWMAYmQcYkjrGWfs',
            'data' => [
                'first' => '服务器出错，请尽快修复',
                'keyword1' => '',
                'keyword2' => '',
                'keyword3' => '',
                'keyword4' => '',
                'remark' => '',
            ]
        ];
        $officialAccount->template_message->send($message);
    }
}
