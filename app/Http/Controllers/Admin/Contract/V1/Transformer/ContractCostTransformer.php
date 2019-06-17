<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 下午1:59
 */

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractCost;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class ContractCostTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['costContent'];

    public function transform(ContractCost $contractCost): array
    {
        $contract = $contractCost->contract;
        return [
            'id' => $contractCost->id,
            'contract_id' => $contractCost->contract_id,
            'contract_number' => $contract->contract_number,
            'contract_name' => $contract->name,
            'applicant' => $contract->applicant,
            'applicant_name' => $contract->applicantUser->name,
            'owner' => $contract->owner,
            'owner_name' => $contract->ownerUser->name,
            'confirm_cost' => $contractCost->confirm_cost,
            'total_cost' => $contractCost->total_cost,
            'created_at' => $contractCost->created_at->toDateTimeString(),
            'updated_at' => $contractCost->updated_at->toDateTimeString()
        ];
    }

    public function includeCostContent(ContractCost $contractCost): Collection
    {
        return $this->collection($contractCost->costContent, new ContractCostContentTransformer());
    }
}