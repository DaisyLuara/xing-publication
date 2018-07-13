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
        $data = $this->data;
        $user = User::query()->where('ar_user_id', $data['ar_user_id'])->first();
        $officialAccount = EasyWeChat::officialAccount();
        $message = [
            'touser' => $user->weixin_openid,
            'template_id' => 'siyJMjigeMMNpXrFSsvz6rvrKQh9Gf5RcfbiVYFQFyY',
            'data' => [
                'first' => '你好，你的上周点位排名情况如下。',
                'keyword1' => "点位名称 【" . $data['point_name'] . "】",
                'keyword2' => "日均围观 【" . $data['looknum_average'] . "】" . "\r\n" . "           点位排名 【倒数第" . $data['ranking'] . "】" . "\r\n" . "           场景分类 【" . $data['scene_name'] . "】" . "\r\n" . "           时间区间 【" . (new Carbon($data['start_date']))->format('m-d') . " 至 " . (new Carbon($data['end_date']))->format('m-d') . "】",
                'remark' => '再接再厉！',
            ]
        ];
        $officialAccount->template_message->send($message);
    }
}
