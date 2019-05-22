<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceHistoryExport extends BaseExport
{
    private $status;//审批状态
    private $name;//公司名称
    private $contract_number;//合同编号
    private $start_date, $end_date; //开始日期,结束日期
    private $applicant;

    public function __construct($request)
    {
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
        $this->status = $request->status;
        $this->name = $request->name;
        $this->contract_number = $request->contract_number;
        $this->applicant = $request->applicant;
        $this->fileName = '票据-我已审批列表';
    }

    public function collection()
    {
        /** @var User $user */
        $user = Auth::user();

        $history_invoice_ids = InvoiceHistory::query()
            ->where('user_id', $user->id)
            ->pluck('invoice_id')->toArray();

        $query = DB::table('invoices as i')
            ->leftJoin('contracts', 'contracts.id', '=', 'i.contract_id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('invoice_companies', 'invoice_companies.id', '=', 'i.invoice_company_id')
            ->leftJoin('users as au', 'i.applicant', '=', 'au.id')
            ->leftJoin('users as hu', 'i.handler', '=', 'hu.id')
            ->leftJoin('invoice_contents as ic', 'ic.invoice_id', '=', 'i.id')
            ->leftJoin('invoice_kinds as ik', 'ik.id', '=', 'ic.invoice_kind_id')
            ->leftJoin('goods_services as gs', 'gs.id', '=', 'ic.goods_service_id')
            ->whereIn('i.id', $history_invoice_ids);

        if ($this->start_date && $this->end_date) {
            $query->whereRaw("date_format(i.created_at,'%Y-%m-%d') between '$this->start_date' and '$this->end_date' ");
        }

        if ($this->name !== null) {
            $query->where('companies.name', 'like', '%' . $this->name . '%');
        }

        if ($this->applicant) {
            $query->where('i.applicant', '=', $this->applicant);
        }

        if ($this->status !== null) {
            $query->where('i.status', '=', $this->status);
        }

        if ($this->contract_number !== null) {
            $query->where('contracts.contract_number', 'like', '%' . $this->contract_number . '%');
        }

        $invoices = $query->orderByDesc('i.created_at')
            ->orderByDesc('i.id')
            ->selectRaw("i.id,concat('\t',contracts.contract_number,'\t') as 'contract_number',companies.name as 'company_name',au.name as 'au_name',
            case i.type when 0 then '专票'  else '普票' end as 'type' ,
            concat('\t',invoice_companies.telephone,'\t') as 'invoice_company_telephone',
            invoice_companies.name as 'invoice_company_name',
            concat('\t',invoice_companies.taxpayer_num,'\t') as 'invoice_company_taxpayer_num',
            concat('\t',invoice_companies.phone,'\t') as 'invoice_company_phone',
            invoice_companies.address as 'invoice_company_address',
            invoice_companies.account_bank as 'invoice_company_account_bank',
            concat('\t',invoice_companies.account_number,'\t') as 'invoice_company_account_number',
            i.total,i.total_text,i.bd_ma_message,i.legal_ma_message,i.remark,
            case i.status when  1 then '待审批' when 2 then '审批中' when 3 then '已审批' when 4 then '已开票' when 5 then '已认领' when 6 then '驳回' else '' end as 'status',
            hu.name as 'handler_name',i.created_at,i.updated_at,
            ik.name as 'ik_name',gs.name as 'gs_name',gs.spec_type,gs.unit as 'gs_unit',ic.num as 'ic_num',ic.price as 'ic_price',ic.money as 'ic_money'
            ")
            ->get()->toArray();


        $header = ['票据ID', '合同编号', '公司名称', '申请人',
            '开票类型', '座机电话', '开票公司', '纳税人识别号',
            '电话', '地址', '开户银行', '开户行账号', '开票总计（小写）', '开票总计（大写）',
            'bd主管意见','法务主管意见','备注', '审批状态', '待处理人', '申请时间', '最后操作时间',
            '开票种类', '货物或应税劳务-服务名称', '规格型号', '单位', '数量', '单价', '金额(含税)'];


        $this->merge = collect($invoices)->groupBy('id')->map(static function ($value) {
            return $value->count();
        })->values()->toArray();
        $this->merge_start = 1;
        $this->merge_end = 21;

        $this->header_num = count($header);
        array_unshift($invoices, $header, $header);
        $this->data = $data = collect($invoices);

        return $data;
    }


}