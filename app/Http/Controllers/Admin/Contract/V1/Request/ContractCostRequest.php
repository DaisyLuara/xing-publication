<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 下午2:18
 */

namespace App\Http\Controllers\Admin\Contract\V1\Request;


use App\Http\Requests\Request;

class ContractCostRequest extends Request
{
    public function rules(): array
    {
        $method = $this->method();
        if ($method === 'POST') {
            return [
                'contract_id' => 'required',
                'total_cost' => 'required',
                'cost_content.*.creator' => 'required',
                'cost_content.*.kind_id' => 'required',
                'cost_content.*.money' => 'required',
                'cost_content.*.remark' => 'max:100'
            ];
        }
        return [];
    }
}