<?php

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use League\Fractal\TransformerAbstract;

class ContractTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['media'];

    protected $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '特批',
        '5' => '驳回'
    ];

    public function transform(Contract $contract)
    {
        return [
            'id' => $contract->id,
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
            'receive_date' => join(',', array_column($contract->receiveDate->toArray(), 'date')),
            'created_at' => $contract->created_at->toDateTimeString(),
            'updated_at' => $contract->updated_at->toDateTimeString(),
        ];
    }

    public function includeMedia(Contract $contract)
    {
        return $this->collection($contract->media, new MediaTransformer());
    }
}