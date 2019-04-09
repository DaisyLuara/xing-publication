<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Contract\V1\Models\ContractCost;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

        $query = ContractCost::query();

        if ($this->start_date && $this->end_date) {
            $query->whereRaw("date_format(updated_at,'%Y-%m-%d') between '$this->start_date' and '$this->end_date' ");
        }
        $query->whereHas('contract', function ($q) {
            if ($this->contract_number) {
                $q->where('contract_number', 'like', '%' . $this->contract_number . '%');
            }

            if ($this->contract_name) {
                $q->where('name', 'like', '%' . $this->contract_name . '%');
            }
        });
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasRole('user|bd-manager')) {
            $query->where('applicant_id', $user->id);
        }

        $contractCosts = $query->orderBy('updated_at', 'desc')->get()
            ->map(function ($contractCost) {
                return [
                    'contract_number' => "\t".$contractCost->contract->contract_number."\t",
                    'contract_name' => $contractCost->contract->name,
                    'applicant_name' => $contractCost->applicant_name,
                    'total_cost' => $contractCost->total_cost,
                    'confirm_cost' => $contractCost->confirm_cost,
                    'created_at' => $contractCost->created_at->toDateTimeString(),
                    'updated_at' => $contractCost->updated_at->toDateTimeString()
                ];
            })->toArray();

        $header = ['合同编号', '合同名称', '所属人', '成本总额', '已确认成本', '创建时间', '修改时间'];
        $this->header_num = count($header);
        array_unshift($contractCosts, $header, $header);
        $this->data = $data = collect($contractCosts);

        return $data;
    }


}