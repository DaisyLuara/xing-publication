<?php
/**
 * Created by IntelliJ IDEA.
 * User: chenzhong
 * Date: 2019/1/22
 * Time: 下午7:29
 */

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use EasyWeChat;
use App\Http\Controllers\Admin\Activity\V1\Models\RedPackBill;
use Log;

class ResendRedpackJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    protected $redPackBill;

    public function __construct(RedPackBill $redPackBill)
    {
        $this->redPackBill = $redPackBill;
    }

    public function handle()
    {
        $payment = EasyWeChat::payment();
        $redpack = $payment->redpack;

        $redpackData = [
            'mch_billno' => $this->redPackBill->mch_billno,
            'send_name' => $this->redPackBill->send_name,
            're_openid' => $this->redPackBill->re_openid,
            'total_num' => $this->redPackBill->total_num,
            'total_amount' => $this->redPackBill->total_amount,
            'wishing' => $this->redPackBill->wishing,
            'act_name' => $this->redPackBill->act_name,
            'remark' => $this->redPackBill->remark,
            'scene_id' => $this->redPackBill->scene_id,
        ];
        Log::info('resend-redpack-data', $redpackData);

        //发送红包
        $result = $redpack->sendNormal($redpackData);
        Log::info('resend-result', $result);
        $this->redPackBill->update($result);

    }

}