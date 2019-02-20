<?php

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use League\Fractal\TransformerAbstract;

class ContractTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['media', 'receiveDate'];

    protected $statusMapping = [
        '1' => '待审批',
        '2' => '审批中',
        '3' => '已审批',
        '4' => '特批',
        '5' => '驳回'
    ];

    protected $typeMapping = [
        '0' => '收款合同',
        '1' => '付款合同',
        '2' => '其它合同',
    ];

    protected $productStatusMapping = [
        '0' => '无硬件',
        '1' => '未出厂',
        '2' => '已出厂',
    ];

    protected $kindMapping = [
        '0' => null,
        '1' => '铺屏',
        '2' => '销售',
        '3' => '租赁',
        '4' => '服务'
    ];

    protected $targetMapping = [
        '1' => '商户',
        '2' => '商场'
    ];

    protected $chargeMapping = [
        '0' => '否',
        '1' => '是'
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
            'type' => $this->typeMapping[$contract->type],
            'kind' => $this->kindMapping[$contract->kind],
            'serve_target' => $contract->serve_target == null ? null : $this->targetMapping[$contract->serve_target],
            'recharge' => $contract->recharge === null ? null : $this->chargeMapping[$contract->recharge],
            'special_num' => $contract->special_num,
            'common_num' => $contract->common_num,
            'amount' => $contract->amount,
            'remark' => $contract->remark,
            'legal_message' => $contract->legal_message,
            'legal_ma_message' => $contract->legal_ma_message,
            'bd_ma_message' => $contract->bd_ma_message,
            'receive_date' => join(',', array_column($contract->receiveDate->toArray(), 'receive_date')),
            'product_status' => $this->productStatusMapping[$contract->product_status],
            'product_content' => $contract->product,
            'start_date' => $contract->start_date,
            'end_date' => $contract->end_date,
            'created_at' => $contract->created_at->toDateTimeString(),
            'updated_at' => $contract->updated_at->toDateTimeString(),
        ];
    }

    public function includeMedia(Contract $contract)
    {
        return $this->collection($contract->media, new MediaTransformer());
    }

    public function includeReceiveDate(Contract $contract)
    {
        return $this->collection($contract->receiveDate, new ContractReceiveDateTransformer());
    }

}