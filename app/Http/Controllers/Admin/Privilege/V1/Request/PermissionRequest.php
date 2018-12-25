<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/21
 * Time: 下午5:02
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Request;


use App\Http\Requests\Request;

class PermissionRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|unique:permissions',
                    'display_name' => 'required|unique:permissions'
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'filled|unique:permissions',
                    'display_name' => 'filled|unique:permissions',
                ];
                break;
            default:
                return [];
        }

    }
}