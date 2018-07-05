<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use EasyWeChat;
use Log;

class WeekRankingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //yq,cz
        //$openId = ['oNN6q0sZDI_OSTV6rl0rPeHjPgH8', 'oNN6q0pq-f0-Z2E2gb0QeOmY4r-M'];
        $data = $this->data;
        //$user = User::query()->where('ar_user_id', $data->uid)->first();
        $officialAccount = EasyWeChat::officialAccount();
        $message = [
            'touser' => "oNN6q0pq-f0-Z2E2gb0QeOmY4r-M",
            'template_id' => 'siyJMjigeMMNpXrFSsvz6rvrKQh9Gf5RcfbiVYFQFyY',
            'data' => [
                'first' => '你好，你的上周点位排名情况如下',
                'keyword1' => $data['point_name'],
                'keyword2' => "日均围观数：" . $data['looknum_average'] . "\r\n" . "点位排名：倒数第" . $data['ranking'] . "\r\n" . "场景分类：" . $data['scene_name'] . "\r\n" . "时间区间：" . $data['start_date'] . "至" . $data['end_date'],
                'remark' => '再接再厉',
            ]
        ];
        $officialAccount->template_message->send($message);
    }
}
