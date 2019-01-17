<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/24
 * Time: 下午4:47
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Request;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class RoleRequest extends \App\Http\Requests\Request
{
    public function rules(Request $request)
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|unique:roles',
                    'display_name' => 'required',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => Rule::unique('roles')->ignore($request->id),
                    'display_name' => 'filled',
                ];
                break;
            default:
                return [];
        }

    }
}