<?php

namespace App\Exports;

use App\Http\Controllers\Admin\Contract\V1\Api\ActionConfig;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ContractExport extends BaseExport
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

        $this->fileName = '合同管理列表';
    }

    public function collection()
    {

        $query = Contract::query()->with('company', 'applicantUser');

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

        if (!is_null($this->product_status)) {
            $query->where('product_status', $this->product_status);
        }

        /** @var User $user */
        $user = Auth::user();
        if ($user->hasRole('user|bd-manager')) {
            $query->whereRaw("(applicant = $user->id or handler = $user->id)");
        } elseif ($user->hasRole('legal-affairs|legal-affairs-manager')) {
            $query->whereRaw("(applicant = $user->id or handler = $user->id or status=3)");
        } elseif ($user->hasRole('purchasing')) {
            //角色为采购时，查询条件为：已审批完成(status=3),product_status为非0（1未出厂or2已出厂）
            $query->whereRaw("(status = 3 and product_status != 0)");
        } else {
            $query->where('status', ActionConfig::CONTRACT_STATUS_AGREE);
        }

        $contract = $query->orderBy('created_at', 'desc')->get()
            ->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'contract_number' => $contract->contract_number,
                    'company_name' => $contract->company->name,
                    'name' => $contract->name,
                    'amount' => $contract->amount,
                    'applicant_name' => $contract->applicantUser->name,
                    'status' => Contract::$statusMapping[$contract->status] ?? $contract->status,
                    'handler_name' => $contract->handler ? $contract->handlerUser->name : null,
                    'product_status' => Contract::$productStatusMapping[$contract->product_status]??$contract->product_status,
                    'created_at' => $contract->created_at->toDateTimeString(),
                    'updated_at' => $contract->updated_at->toDateTimeString(),
                    'receive_date' => join(',', array_column($contract->receiveDate->toArray(), 'receive_date')),
                ];
            })->toArray();

        $header = ['合同ID', '合同编号', '公司名称', '合同名称', '收款总额', '申请人', '审批状态', '待处理人', '硬件状态',
            '申请时间', '最后操作时间', '预估收款日期'];
        $this->header_num = count($header);
        array_unshift($contract, $header);
        $this->data = $data = collect($contract);

        return $data;
    }


}