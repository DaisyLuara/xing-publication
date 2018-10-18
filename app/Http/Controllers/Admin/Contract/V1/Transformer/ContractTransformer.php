<?php

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use League\Fractal\TransformerAbstract;

class ContractTransformer extends TransformerAbstract
{
    protected $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '特批'
    ];

    public function transform(Contract $contract)
    {
        return [
            'id' => $contract->id,
            'contract_number' => $contract->contract_number,
            'name' => $contract->name,
            'company_name' => $contract->company->name,
            'applicant' => $contract->applicant,
            'status' => $this->statusMapping[$contract->status],
            'handler' => $contract->handler,
            'handler_name' => $contract->handler? $contract->handlerUser->name:null,
            'type' => $contract->type == 0 ? '收款合同' : '付款合同',
            'receive_date' => $contract->receive_date,
            'content' => $contract->content,
            'remark' => $contract->remark,
            'create_user' => $contract->createUser->name,
            'created_at' => $contract->created_at,
            'updated_at' => $contract->updated_at,
        ];
    }
}