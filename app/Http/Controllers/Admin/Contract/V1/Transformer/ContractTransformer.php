<?php

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;

use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use League\Fractal\TransformerAbstract;

class ContractTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['media', 'receiveDate', 'company'];



    public function transform(Contract $contract)
    {
        $team_projects_num = TeamProject::query()->where('contract_id', $contract->id)
            ->whereIn('individual_attribute', [1, 2])
            ->groupBy('individual_attribute')
            ->selectRaw('individual_attribute,count(*) as num')
            ->pluck('num', 'individual_attribute')->toArray();

        return [
            'id' => $contract->id,
            'contract_number' => $contract->contract_number,
            'name' => $contract->name,
            'company_id' => $contract->company_id,
            'company_name' => $contract->company->name,
            'applicant' => $contract->applicant,
            'applicant_name' => $contract->applicantUser->name,
            'status' => Contract::$statusMapping[$contract->status],
            'handler' => $contract->handler,
            'handler_name' => $contract->handler ? $contract->handlerUser->name : null,
            'type' => Contract::$typeMapping[$contract->type],
            'kind' => Contract::$kindMapping[$contract->kind],
            'serve_target' => $contract->serve_target == null ? null : Contract::$targetMapping[$contract->serve_target],
            'recharge' => $contract->recharge === null ? null : Contract::$chargeMapping[$contract->recharge],
            'special_num' => $contract->special_num,
            'true_special_num' => $team_projects_num[1] ?? 0,
            'common_num' => $contract->common_num,
            'true_common_num' => $team_projects_num[2] ?? 0,
            'amount' => $contract->amount,
            'remark' => $contract->remark,
            'legal_message' => $contract->legal_message,
            'legal_ma_message' => $contract->legal_ma_message,
            'bd_ma_message' => $contract->bd_ma_message,
            'receive_date' => join(',', array_column($contract->receiveDate->toArray(), 'receive_date')),
            'product_status' => Contract::$productStatusMapping[$contract->product_status],
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

    public function includeCompany(Contract $contract)
    {
        return $this->item($contract->company, new CompanyTransformer());
    }


}