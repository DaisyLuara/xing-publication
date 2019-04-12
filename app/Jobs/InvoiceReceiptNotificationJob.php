<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\User;
use EasyWeChat;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InvoiceReceiptNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $contractId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $contractId)
    {
        $this->type = $type;
        $this->contractId = $contractId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   //新建通知法务
        $legalPhone = [18301766780, 13916320677];
        if ($this->type === 'legal-affair') {
            $legals = User::query()->whereIn('phone', $legalPhone)->get();
            foreach ($legals as $legal) {
                $this->sendMessage($legal, '有一笔新的收款待认领');
            }
        }

        //认领通知bd和运营
        if ($this->type === 'bd') {
            $contract = Contract::find($this->contractId);
            $bd = User::find($contract->applicant);
            $this->sendMessage($bd, '合同' . $contract->contract_number . '有一笔收款已认领');
            $operation = User::query()->where('phone', 13661874698)->first();
            $this->sendMessage($operation, '合同' . $contract->contract_number . '有一笔收款已认领');
        }
    }

    private function sendMessage($user, $content)
    {
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
