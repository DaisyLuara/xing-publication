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
    public function transform(ContractReceiveDate $contractReceiveDate)
    {
        return [
            'contract_id' => $contractReceiveDate->contract_id,
            'date' => $contractReceiveDate->date,
        ];
    }
}