<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/24
 * Time: 下午4:47
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Request;


use App\Http\Requests\Request;

class RoleRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|regex:/^[a-zA-Z]+$/|unique:roles',
                    'display_name' => 'required|unique:roles',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'regex:/^[a-zA-Z]+$/|unique:roles',
                    'display_name' => 'unique:roles',
                ];
                break;
            default:
                return [];
        }

    }
}