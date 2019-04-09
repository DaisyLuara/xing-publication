<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ContractHistoryExport extends BaseExport
{
    private $status;//审批状态
    private $name; //公司名称
    private $contract_number;//合同编号
    private $product_status;//硬件状态
    private $start_date, $end_date; //开始时间,结束时间


    public function __construct($request)
    {
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
        $this->status = $request->status;
        $this->name = $request->name;
        $this->contract_number = $request->contract_number;
        $this->product_status = $request->product_status;

        $this->fileName = '合同-我已审批列表';
    }

    public function collection()
    {
        $query = Contract::query();
        if ($this->start_date && $this->end_date) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$this->start_date' and '$this->end_date' ");
        }

        if (!is_null($this->name)) {
            $query->whereHas('company', function ($q) {
                $q->where('name', 'like', '%' . $this->name . '%');
            });
        }

        if (!is_null($this->status)) {
            $query->where('status', $this->status);
        }

        if (!is_null($this->contract_number)) {
            $query->where('contract_number', 'like', '%' . $this->contract_number . '%');
        }

        /** @var  User $user */
        $user = Auth::user();
        $query->whereHas('contractHistory', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        $contracts = $query->orderBy('created_at', 'desc')->get()
            ->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'contract_number' => "\t" . $contract->contract_number . "\t",
                    'company_name' => $contract->company->name,
                    'applicant_name' => $contract->applicantUser->name,
                    'name' => $contract->name,
                    'type' => Contract::$typeMapping[$contract->type] ?? $contract->type,
                    'kind' => Contract::$kindMapping[$contract->kind] ?? $contract->kind,
                    'remark' => $contract->remark,
                    'special_num' => $contract->special_num,
                    'common_num' => $contract->common_num,
                    'legal_ma_message' => $contract->legal_ma_message,
                    'bd_ma_message' => $contract->bd_ma_message,
                    'status' => Contract::$statusMapping[$contract->status] ?? $contract->status,
                    'handler_name' => $contract->handler ? $contract->handlerUser->name : null,
                    'product_status' => Contract::$productStatusMapping[$contract->product_status] ?? $contract->product_status,
                    'created_at' => $contract->created_at->toDateTimeString(),
                    'updated_at' => $contract->updated_at->toDateTimeString(),
                    'receive_date' => join(',', array_column($contract->receiveDate->toArray(), 'receive_date')),
                ];
            })->toArray();

        $header = ['合同ID', '合同编号', '公司名称', '申请人', '合同名称', '合同类型', '合同种类',
            '备注', '定制节目数量', '通用节目数量', '法务主管意见', 'bd主管意见',
            '审批状态', '待处理人', '硬件状态',
            '申请时间', '最后操作时间', '收款日期'];
        $this->header_num = count($header);
        array_unshift($contracts, $header, $header);
        $this->data = $data = collect($contracts);

        return $data;
    }


}