<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Contract\V1\Models\ContractHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractHistoryExport extends BaseExport
{
    private $status;//审批状态
    private $name; //公司名称
    private $applicant; //公司名称
    private $contract_number;//合同编号
    private $product_status;//硬件状态
    private $start_date, $end_date; //开始时间,结束时间


    public function __construct($request)
    {
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
        $this->status = $request->status;
        $this->name = $request->name;
        $this->applicant = $request->applicant;
        $this->contract_number = $request->contract_number;
        $this->product_status = $request->product_status;

        $this->fileName = '合同-我已审批列表';
    }

    public function collection()
    {
        /** @var  $currentUser \App\Models\User */
        $currentUser = Auth::user();

        $history_contract_ids = ContractHistory::query()
            ->where('user_id', $currentUser->id)
            ->pluck('contract_id')->toArray();

        $query = DB::table('contracts as c')
            ->leftJoin('companies', 'c.company_id', '=', 'companies.id')
            ->leftJoin('users as au', 'c.applicant', '=', 'au.id')
            ->leftJoin('users as ou', 'c.owner', '=', 'ou.id')
            ->leftJoin('users as hu', 'c.handler', '=', 'hu.id')
            ->leftJoin('contract_products as cp', 'c.id', '=', 'cp.contract_id')
            ->whereIn('c.id', $history_contract_ids)
            ->whereRaw('c.deleted_at is null');

        if ($this->start_date && $this->end_date) {
            $query->whereRaw("date_format(c.created_at,'%Y-%m-%d') between '$this->start_date' and '$this->end_date' ");
        }

        if ($this->name !== null) {
            $query->where('companies.name', 'like', '%' . $this->name . '%');
        }

        if ($this->applicant) {
            $query->where('c.applicant', '=', $this->applicant);
        }

        if ($this->status !== null) {
            $query->where('c.status', $this->status);
        }

        if ($this->product_status !== null) {
            $query->where('c.product_status', $this->product_status);
        }

        if ($this->contract_number !== null) {
            $query->where('c.contract_number', 'like', '%' . $this->contract_number . '%');
        }

        $contracts = $query->orderByDesc('c.created_at')
            ->orderByDesc('c.id')
            ->selectRaw("c.id,concat('\t',c.contract_number,'\t') as 'contract_number',companies.name as 'company_name',c.name,au.name as 'au_name',ou.name as 'ou_name',
            case c.type when 0 then '收款合同' when 1 then '付款合同' when 2 then '其它合同' else c.type end as 'type' ,
            case c.kind when 1 then '铺屏' when 2 then '销售' when 3 then '租赁' when 4 then '服务' else '' end as 'kind',
            c.remark,c.special_num,c.common_num,c.legal_ma_message,c.bd_ma_message,
            case c.status when  1 then '待审批' when 2 then '审批中' when 3 then '已审批' when 4 then '特批' when 5 then '驳回'  else '' end as 'c_status',
            hu.name as 'handler_name',
            case c.status when 0 then '无硬件' when 1 then '未出厂' when 2 then '已出厂' else '' end as 'product_status',
            c.created_at,c.updated_at,
            cp.product_name,cp.product_color,cp.product_stock")
            ->get()->toArray();

        $this->merge = collect($contracts)->groupBy('id')->map(function ($value) {
            return $value->count();
        })->values()->toArray();
        $this->merge_start = 1;
        $this->merge_end = 17;

        $header = ['合同ID', '合同编号', '公司名称', '合同名称', '申请人', '所属人', '合同类型', '合同种类',
            '备注', '定制节目数量', '通用节目数量', '法务主管意见', 'bd主管意见',
            '审批状态', '待处理人', '硬件状态',
            '申请时间', '最后操作时间',
            '产品名称', '产品颜色', '产品数量'];
        $this->header_num = count($header);
        array_unshift($contracts, $header, $header);
        $this->data = $data = collect($contracts);

        return $data;
    }


}