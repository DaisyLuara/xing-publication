<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/2/18
 * Time: 上午10:57
 */

namespace App\Http\Controllers\Admin\Warehouse\V1\Request;


use App\Http\Requests\Request;

class AttributeRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required',
                    'display_name' => 'required'
                ];
                break;
            case 'patch':
                return [
                    'name' => 'filled',
                    'display_name' => 'filled'
                ];
                break;
            default:
                return [];
        }
    }
}