<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 下午1:46
 */

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind;
use League\Fractal\TransformerAbstract;

class ContractCostKindTransformer extends TransformerAbstract
{
    public function transform(ContractCostKind $contractCostKind): array
    {
        return [
            'id' => $contractCostKind->id,
            'name' => $contractCostKind->name,
        ];
    }
}