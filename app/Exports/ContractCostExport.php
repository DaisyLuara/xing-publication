<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractCostExport extends BaseExport
{
    private $contract_name;//合同名称
    private $contract_number;//合同编号
    private $start_date, $end_date; //修改开始日期,修改结束日期

    public function __construct($request)
    {
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
        $this->contract_name = $request->contract_name;
        $this->contract_number = $request->contract_number;
        $this->fileName = '合同-成本管理列表';
    }

    public function collection()
    {

        /** @var  $currentUser \App\Models\User */
        $currentUser = Auth::user();

        $query = DB::table('contract_costs as cc')
            ->leftJoin('contracts', 'contracts.id', '=', 'cc.contract_id')
            ->leftJoin('contract_cost_contents as cct', 'cct.cost_id', '=', 'cc.id')
            ->leftJoin('contract_cost_kinds as cck', 'cck.id', '=', 'cct.kind_id');

        if ($this->start_date && $this->end_date) {
            $query->whereRaw("date_format(cc.updated_at,'%Y-%m-%d') between '$this->start_date' and '$this->end_date' ");
        }

        if ($this->contract_number) {
            $query->where('contracts.contract_number', 'like', '%' . $this->contract_number . '%');
        }

        if ($this->contract_name) {
            $query->where('contracts.name', 'like', '%' . $this->contract_name . '%');
        }

        if ($currentUser->hasRole('user|bd-manager')) {
            $query->where('cc.applicant_id', $currentUser->id);
        }

        $contractCosts = $query->orderByDesc('cc.updated_at')
            ->selectRaw("cc.id,concat('\t',contracts.contract_number,'\t') as 'contract_number',contracts.name as 'contract_name',
            cc.applicant_name,cc.total_cost, cc.confirm_cost,cc.created_at,cc.updated_at,
            cct.creator,cck.name as 'cck_name',cct.money,cct.remark,
            case cct.status when 1 then '已确认' when 0 then '未确认' end as 'ccr_status',
            cct.created_at as 'cct_created_at'")
            ->get()->toArray();

        $this->merge = collect($contractCosts)->groupBy('id')->map(function ($value) {
            return $value->count();
        })->values()->toArray();
        $this->merge_start = 1;
        $this->merge_end = 8;

        $header = ['成本管理ID', '合同编号', '合同名称', '所属人', '成本总额', '已确认成本', '创建时间', '修改时间',
            '成本创建人', '成本类型', '成本金额', '备注', '状态', '操作时间'];
        $this->header_num = count($header);
        array_unshift($contractCosts, $header, $header);
        $this->data = $data = collect($contractCosts);

        return $data;
    }


}