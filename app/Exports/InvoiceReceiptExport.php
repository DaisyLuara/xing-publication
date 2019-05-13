<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceReceipt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InvoiceReceiptExport extends BaseExport
{

    private $claim_status;//认领状态
    private $name;//付款公司
    private $start_date, $end_date; //开始日期,结束日期
    private $applicant;

    public function __construct($request)
    {
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
        $this->claim_status = $request->claim_status;
        $this->name = $request->name;
        $this->applicant = $request->applicant;
        $this->fileName = '票据-收款管理列表';
    }

    public function collection()
    {


        $query = InvoiceReceipt::query();
        if ($this->start_date && $this->end_date) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$this->start_date' and '$this->end_date' ");
        }

        if ($this->name) {
            $query->where('receipt_company', 'like', '%' . $this->name . '%');
        }

        if ($this->claim_status) {
            $query->where('claim_status', $this->claim_status);
        }

        if ($this->applicant) {
            $query->whereHas('receiveDate', function ($q) {
                $q->whereHas('contract', function ($q) {
                    $q->where('applicant', $this->applicant);
                });
            });
        }

        /** @var User $user */
        $user = Auth::user();
        if ($user->hasRole('user')) {
            $query->whereHas('receiveDate', static function ($q) use ($user) {
                $q->whereHas('contract', static function ($q) use ($user) {
                    $q->where('applicant', $user->id);
                });
            });
        }

        if ($user->hasRole('bd-manager')) {
            $query->whereHas('receiveDate', static function ($q) use ($user) {
                $q->whereHas('contract', static function ($q) use ($user) {
                    $q->whereHas('applicantUser', static function ($q) use ($user) {
                        $q->where('parent_id', $user->id);
                    });
                });
            });
        }

        $invoiceReceipts = $query->orderByDesc('id')->get()
            ->map(static function ($invoiceReceipt) {
                return [
                    'id' => $invoiceReceipt->id,
                    'receipt_company' => $invoiceReceipt->receipt_company,
                    'receipt_money' => $invoiceReceipt->receipt_money,
                    'receipt_date' => $invoiceReceipt->receipt_date,
                    'claim_status' => $invoiceReceipt->claim_status === 0 ? '未认领' : '已认领',
                    'creator' => $invoiceReceipt->creator,
                    'receiveDate_receive_date' => $invoiceReceipt->receiveDate ? $invoiceReceipt->receiveDate->receive_date : '',
                    'receiveDate_contract_contract_number' => "\t" . (($invoiceReceipt->receiveDate && $invoiceReceipt->receiveDate->contract) ? $invoiceReceipt->receiveDate->contract->contract_number : '') . "\t",
                    'receiveDate_contract_applicant_name' => ($invoiceReceipt->receiveDate && $invoiceReceipt->receiveDate->contract && $invoiceReceipt->receiveDate->contract->applicantUser) ? $invoiceReceipt->receiveDate->contract->applicantUser->name : '',
                ];
            })->toArray();

        $header = ['ID', '付款公司', '收款金额', '到账日期', '认领状态', '收款创建人', '预估收款时间', '合同编号', '所属人'];

        $this->header_num = count($header);
        array_unshift($invoiceReceipts, $header, $header);
        $this->data = $data = collect($invoiceReceipts);

        return $data;
    }


}