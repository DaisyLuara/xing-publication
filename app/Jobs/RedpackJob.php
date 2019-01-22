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

class RedpackJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    protected $rank;
    protected $openID;

    public function __construct(int $rank, string $openID)
    {
        $this->rank = $rank;
        $this->openID = $openID;
    }

    public function handle()
    {
        $rankArr = [1000, 800, 600, 400, 200];
        $payment = EasyWeChat::payment();
        $redpack = $payment->redpack;
        $totalAmount = 200 * 100;

        for ($i = 0; $i < $rankArr[$this->rank] / 200; $i++) {

            $mchBillno = date('YmdHis') . uniqid();
            $redpackData = [
                'mch_billno' => $mchBillno,
                'send_name' => '测试',
                're_openid' => $this->openID,
                'total_num' => 1,
                'total_amount' => $totalAmount,
                'wishing' => '新年快乐!',
                'act_name' => '排行榜！',
                'remark' => '排行榜',
                'scene_id' => 'PRODUCT_4',
            ];

            //发送红包
            $result = $redpack->sendNormal($redpackData);

            $redpackBillData = array_merge($redpackData, $result);

            RedPackBill::query()->create($redpackBillData);
        }

    }

}