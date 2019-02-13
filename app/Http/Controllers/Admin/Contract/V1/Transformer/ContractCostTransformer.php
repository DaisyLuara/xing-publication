<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 下午1:59
 */

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractCost;
use League\Fractal\TransformerAbstract;

class ContractCostTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['costContent'];

    public function transform(ContractCost $contractCost)
    {
        return [
            'id' => $contractCost->id,
            'contract_id' => $contractCost->contract_id,
            'contract_number' => $contractCost->contract->contract_number,
            'contract_name' => $contractCost->contract->name,
            'applicant_id' => $contractCost->applicant_id,
            'applicant_name' => $contractCost->applicant_name,
            'total_cost' => $contractCost->total_cost,
            'created_at' => $contractCost->created_at->toDateTimeString(),
            'updated_at' => $contractCost->updated_at->toDateTimeString()
        ];
    }

    public function includeCostContent(ContractCost $contractCost)
    {
        return $this->collection($contractCost->costContent, new ContractCostContentTransformer());
    }
}