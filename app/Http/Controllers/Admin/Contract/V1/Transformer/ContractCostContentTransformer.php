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
    protected $kindMapping = [
        '1' => '物流费用',
        '2' => '运维费用',
        '3' => '4G网络费用',
        '4' => '人员差旅',
        '5' => '物料费用',
        '6' => '其他',
    ];

    public function transform(ContractCostContent $content)
    {
        return [
            'id' => $content->id,
            'creator_id' => $content->creator_id,
            'creator' => $content->creator,
            'kind_id' => $content->kind_id,
            'kind' => $this->kindMapping[$content->kind_id],
            'money' => $content->money,
            'remark' => $content->remark,
            'status' => $content->status,
            'created_at' => $content->created_at->toDateTimeString(),
            'updated_at' => $content->updated_at->toDateTimeString()
        ];
    }
}