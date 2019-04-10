<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InvoiceHistoryExport extends BaseExport
{
    private $status;//审批状态
    private $name;//公司名称
    private $contract_number;//合同编号
    private $receive_status;
    private $start_date, $end_date; //开始日期,结束日期


    public function __construct($request)
    {
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
        $this->status = $request->status;
        $this->name = $request->name;
        $this->receive_status = $request->receive_status;
        $this->contract_number = $request->contract_number;

        $this->fileName = '票据-我已审批列表';
    }

    public function collection()
    {

        $query = Invoice::query();

        if ($this->start_date && $this->end_date) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$this->start_date' and '$this->end_date' ");
        }

        if (!is_null($this->name)) {
            $query->whereHas('contract', function ($q) {
                $q->whereHas('company', function ($q) {
                    $q->where('name', 'like', '%' . $this->name . '%');
                });
            });
        }

        if (!is_null($this->status)) {
            $query->where('status', '=', $this->status);
        }

        if (!is_null($this->contract_number)) {
            $query->whereHas('contract', function ($q) {
                $q->where('contract_number', 'like', '%' . $this->contract_number . '%');
            });
        }

        if (!is_null($this->receive_status)) {
            $query->where('receive_status', '=', $this->receive_status);
        }

        /** @var User $user */
        $user = Auth::user();
        if ($user->id == getProcessStaffId('finance', 'invoice')) {
            $query->whereRaw("(handler=$user->id or status=4 or status=5)");
        } else if ($user->hasRole('operation')) {
            $query->whereRaw('(status=3 or status=4 or status=5)');
        } else {
            $query->whereRaw("(applicant=$user->id or handler=$user->id)");
        }
        $invoices = $query->orderBy('created_at', 'desc')->get()
            ->map(function ($invoice) {
                $invoiceCompany = $invoice->invoiceCompany;
                return [
                    'id' => $invoice->id,
                    'contract_number' => "\t" . $invoice->contract->contract_number . "\t",
                    'company_name' => $invoice->contract->company->name,
                    'applicant_name' => $invoice->applicantUser->name,
                    'type' => $invoice->type == 0 ? '专票' : '普票',
                    'invoice_company_telephone' => $invoiceCompany->telephone ?? null,
                    'invoice_company_name' => $invoiceCompany->name ?? null,
                    'invoice_company_taxpayer_num' => $invoiceCompany->taxpayer_num ?? null,
                    'invoice_company_phone' => $invoiceCompany->phone ?? null,
                    'invoice_company_address' => $invoiceCompany->address ?? null,
                    'invoice_company_account_bank' => $invoiceCompany->account_bank ?? null,
                    'invoice_company_account_number' => "\t" . ($invoiceCompany->account_number ?? null). "\t",
                    'total' => $invoice->total,
                    'total_text' => $invoice->total_text,
                    'remark' => $invoice->remark,
                    'status' => Invoice::$statusMapping[$invoice->status] ?? $invoice->status,
                    'handler_name' => $invoice->handler ? $invoice->handlerUser->name : null,
                    'created_at' => $invoice->created_at->toDateTimeString(),
                    'updated_at' => $invoice->updated_at->toDateTimeString(),
                ];
            })->toArray();


        $header = ['票据ID', '合同编号', '公司名称', '申请人', '开票类型', '座机电话', '开票公司', '纳税人识别号',
            '电话', '地址', '开户银行', '开户行账号', '开票总计（小写）', '开票总计（大写）',
            '备注', '审批状态', '待处理人', '申请时间', '最后操作时间'];


        $this->header_num = count($header);
        array_unshift($invoices, $header, $header);
        $this->data = $data = collect($invoices);
        return $data;
    }


}