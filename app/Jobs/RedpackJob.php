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

    protected $redPackData;

    /**
     * RedpackJob constructor.
     * @param int $rank
     * @param string $openID
     * @param array $redPackData
     */
    public function __construct(array $redPackData)
    {
        $this->redPackData = $redPackData;
    }

    public function handle()
    {
        $payment = EasyWeChat::payment();
        $redPack = $payment->redpack;

        $totalAmount = $this->redPackData['total_amount'];
        $length = ceil($totalAmount / 200);
        $perTotalAmount = $totalAmount <= 200 ? $totalAmount : 200;

        for ($i = 1; $i <= $length; $i++) {

            $mchBillNo = date('YmdHis') . uniqid();
            $redPackData = [
                'mch_billno' => $mchBillNo,
                'send_name' => $this->redPackData['send_name'],
                're_openid' => $this->redPackData['re_openid'],
                'total_num' => 1,
                'total_amount' => $perTotalAmount,
                'wishing' => $this->redPackData['wishing'],
                'act_name' => $this->redPackData['act_name'],
                'remark' => $this->redPackData['remark'],
                'scene_id' => $this->redPackData['scene_id'],
            ];

            //发送红包
            $result = $redPack->sendNormal($redPackData);

            $redPackBillData = array_merge($redPackData, $result);

            RedPackBill::query()->create($redPackBillData);
        }

    }

}