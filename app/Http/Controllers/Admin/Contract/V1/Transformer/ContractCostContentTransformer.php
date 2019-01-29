<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 下午3:03
 */

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent;
use League\Fractal\TransformerAbstract;

class ContractCostContentTransformer extends TransformerAbstract
{
    public function transform(ContractCostContent $content)
    {
        return [
            'id' => $content->id,
            'creator' => $content->creator,
            'kind_id' => $content->kind_id,
            'money' => $content->money,
            'remark' => $content->remark,
            'status' => $content->status,
            'operator' => $content->operator,
            'created_at' => $content->created_at->toDateTimeString(),
            'updated_at' => $content->updated_at->toDateTimeString()
        ];
    }
}