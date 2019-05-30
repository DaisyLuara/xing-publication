<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentHistoryExport extends BaseExport
{
    private $status;//审批状态
    private $receive_status;//收票状态
    private $payment_payee_name; //收款人
    private $contract_number;//合同编号
    private $start_date, $end_date; //开始时间,结束时间
    private $owner;

    public function __construct($request)
    {
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
        $this->status = $request->status;
        $this->receive_status = $request->receive_status;
        $this->payment_payee_name = $request->payment_payee_name;
        $this->contract_number = $request->contract_number;
        $this->owner = $request->owner;
        $this->fileName = '付款-我已审核列表';
    }

    public function collection()
    {


        $query = Payment::query();


        if ($this->start_date && $this->end_date) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$this->start_date' and '$this->end_date' ");
        }

        if ($this->owner) {
            $query->where('owner', '=', $this->owner);
        }

        if ($this->payment_payee_name) {
            $payee = $this->payment_payee_name;
            $query->whereHas('paymentPayee', static function ($q) use ($payee) {
                $q->where('name', 'like', '%' . $payee . '%');
            });
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
        $query->whereHas('paymentHistory', static function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

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
                    'bd_ma_message' => $payment->bd_ma_message,
                    'legal_message' => $payment->legal_message,
                    'legal_ma_message' => $payment->legal_ma_message,
                    'auditor_message' => $payment->auditor_message,
                    'created_at' => $payment->created_at->toDateTimeString(),
                    'updated_at' => $payment->updated_at->toDateTimeString(),
                    'medias' => $payment->media ? implode(';', $payment->media->pluck('url')->toArray()) : '',
                    'remark' => $payment->remark,
                ];

            })->toArray();

        $header = ['ID', '合同编号', '申请人', '所属人', '申请金额（大写）', '票据种类', '申请事由',
            '收款人', '收款人开户行', '收款人账号', '收票状态', '审批状态', '待处理人', '付款人',
            'bd主管意见', '法务意见', '法务主管意见', '审计意见',
            '申请时间', '最后操作时间', '附件内容', '备注',];

        $this->header_num = count($header);
        array_unshift($payments, $header, $header);
        $this->data = $data = collect($payments);

        return $data;
    }


}