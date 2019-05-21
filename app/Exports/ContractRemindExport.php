<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractRemindExport extends BaseExport
{

    private $name;//合同名称
    private $company_name;//公司名称
    private $contract_number;//合同编号
    private $applicant;


    public function __construct(Request $request)
    {
        $this->name = $request->get('name');
        $this->company_name = $request->get('company_name');
        $this->contract_number = $request->get('contract_number');
        $this->applicant = $request->get('applicant');

        $this->fileName = '公司-收款合同列表';
    }

    public function collection()
    {

        /** @var  $currentUser \App\Models\User */
        $currentUser = Auth::user();

        $query = DB::table('contracts as c')
            ->leftJoin('companies', 'c.company_id', '=', 'companies.id')
            ->leftJoin('users as au', 'c.applicant', '=', 'au.id')
            ->leftJoin('contract_receive_dates as crd', 'c.id', '=', 'crd.contract_id')
            ->leftJoin('invoice_receipts as ir', 'ir.id', '=', 'crd.invoice_receipt_id');

        if ($this->name) {
            $query->where('c.name', 'like', '%' . $this->name . '%');
        }
        if ($this->applicant) {
            $query->where('c.applicant', '=', $this->applicant);
        }
        if ($this->company_name) {
            $query->where('companies.name', 'like', '%' . $this->company_name . '%');
        }
        if ($this->contract_number  !== null ) {
            $query->where('c.contract_number', 'like', '%' . $this->contract_number . '%');
        }

        if ($currentUser->hasRole('user')) {
            $query->where('applicant', $currentUser->id);
        }

        if ($currentUser->hasRole('bd-manager')) {
            $query->where('au.parent_id', $currentUser->id);
        }

        $contractReceiveDates = $query->where('c.status', 3)
            ->orderByDesc('c.id')
            ->selectRaw("c.id,concat('\t',c.contract_number,'\t') as 'contract_number',companies.name as 'company_name',c.name,au.name as 'au_name', c.amount,
            crd.receive_date,case crd.receive_status when  1 then '已收款' when 0 then '未收款' else '' end as 'crd_receive_status',
            ir.receipt_money,ir.receipt_company,ir.receipt_date as 'ir_receipt_date'")
            ->get()->toArray();

        $header = ['合同ID', '合同编号', '公司名称', '合同名称', '申请人', '合同金额',
            '预估收款日期', '收款状态', '收款金额', '付款公司', '到账时间'];

        $this->merge = collect($contractReceiveDates)->groupBy('id')->map(static function ($value) {
            return $value->count();
        })->values()->toArray();
        $this->merge_start = 1;
        $this->merge_end = 6;

        $this->header_num = count($header);
        array_unshift($contractReceiveDates, $header, $header);
        $this->data = $data = collect($contractReceiveDates);

        return $data;
    }


}