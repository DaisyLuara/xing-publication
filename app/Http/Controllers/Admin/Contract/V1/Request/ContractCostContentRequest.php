<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 下午3:43
 */

namespace App\Http\Controllers\Admin\Contract\V1\Request;


use App\Http\Requests\Request;

class ContractCostContentRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'creator' => 'required',
                    'kind_id' => 'required',
                    'money' => 'required',
                    'remark' => 'max:100'
                ];
                break;
            case 'PATCH':
                return [
                    'money'=>'filled|numeric',
                    'total_cost'=>'filled|numeric'
                ];
                break;
            default:
                return [];
        }

    }

    public function attributes(){
        return [
            'money'=>'金额'
        ];
    }
}