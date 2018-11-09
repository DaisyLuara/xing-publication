<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/10/24
 * Time: 下午2:15
 */

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use League\Fractal\TransformerAbstract;

class ContractReceiveDateTransformer extends TransformerAbstract
{
    protected $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '特批',
        '5' => '驳回'
    ];

    public function transform(ContractReceiveDate $contractReceiveDate)
    {
        $contract = $contractReceiveDate->contract()->first();
        return [
            'id' => $contractReceiveDate->id,
            'contract_id' => $contract->id,
            'contract_number' => $contract->contract_number,
            'name' => $contract->name,
            'company_id' => $contract->company_id,
            'company_name' => $contract->company->name,
            'applicant' => $contract->applicant,
            'applicant_name' => $contract->applicantUser->name,
            'status' => $this->statusMapping[$contract->status],
            'handler' => $contract->handler,
            'handler_name' => $contract->handler ? $contract->handlerUser->name : null,
            'type' => $contract->type == 0 ? '收款合同' : '付款合同',
            'remark' => $contract->remark,
            'receive_date' => $contractReceiveDate->receive_date,
            'receive_status' => $contractReceiveDate->receive_status == 0 ? '未收款' : '已收款',
//            'created_at' => $contract->created_at->toDateTimeString(),
//            'updated_at' => $contract->updated_at->toDateTimeString(),
        ];
    }
}