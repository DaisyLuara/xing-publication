<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentExport extends BaseExport
{
    private $status;//审批状态
    private $receive_status;//收票状态
    private $payee; //收款人
    private $contract_number;//合同编号
    private $start_date, $end_date; //开始时间,结束时间
    private $owner;

    public function __construct($request)
    {
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
        $this->status = $request->status;
        $this->receive_status = $request->receive_status;
        $this->payee = $request->payee;
        $this->contract_number = $request->contract_number;
        $this->owner = $request->owner;
        $this->fileName = '付款-付款管理列表';
    }

    public function collection()
    {


        $query = Payment::query();

        if ($this->start_date && $this->end_date) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$this->start_date' and '$this->end_date' ");
        }

        if ($this->payee) {
            $query->where('payee', 'like', '%' . $this->payee . '%');
        }
        if ($this->owner) {
            $query->where('owner', '=', $this->owner);
        }
        if ($this->receive_status !== null) {
            $query->where('receive_status', '=', $this->receive_status);
        }
        if ($this->status !== null) {
            $query->where('status', '=', $this->status);
        }
        if ($this->contract_number !== null) {
            $query->whereHas('contract', function ($q) {
                $q->where('contract_number', 'like', '%' . $this->contract_number . '%');
            });
        }

        /** @var  User $user */
        $user = Auth::user();
        if ($user->id === getProcessStaffId('finance', 'payment')) {
            $query->whereRaw("(handler=$user->id or status=4)");
        } else if ($user->hasRole('operation')) {
            $query->whereRaw('(status=3 or status=4)');
        } else {
            $query->whereRaw("(owner=$user->id or handler=$user->id)");
        }

        $payments = $query->orderBy('created_at', 'desc')->get()
            ->map(static function ($payment) {
                return [
                    'id' => $payment->id,
                    'contract_number' => $payment->contract->contract_number,
                    'applicant_name' => $payment->applicantUser->name,
                    'owner_name' => $payment->ownerUser->name,
                    'amount' => $payment->amount,
                    'type' => Payment::$typeMapping[$payment->type],
                    'reason' => $payment->reason,
                    'payment_payee_name' => $payment->paymentPayee ? $payment->paymentPayee->name : null,
                    'payment_payee_account_bank' => $payment->paymentPayee ? $payment->paymentPayee->account_bank : null,
                    'payment_payee_account_number' => "\t" . ($payment->paymentPayee ? $payment->paymentPayee->account_number : '') . "\t",
                    'receive_status' => $payment->receive_status === 0 ? '未收票' : '已收票',
                    'status' => Payment::$statusMapping[$payment->status] ?? $payment->status,
                    'handler_name' => $payment->handler ? $payment->handlerUser->name : null,
                    'payer' => $payment->payer,
                    'created_at' => $payment->created_at->toDateTimeString(),
                    'updated_at' => $payment->updated_at->toDateTimeString(),
                    'medias' => $payment->media ? implode(';', $payment->media->pluck('url')->toArray()) : '',
                    'remark' => $payment->remark,
                ];

            })->toArray();

        $header = ['ID', '合同编号', '申请人', '所属人', '申请金额（大写）', '票据种类', '申请事由',
            '收款人', '收款人开户行', '收款人账号', '收票状态', '审批状态', '待处理人', '付款人',
            '申请时间', '最后操作时间', '附件内容', '备注',];

        $this->header_num = count($header);
        array_unshift($payments, $header, $header);
        $this->data = $data = collect($payments);

        return $data;
    }


}