<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/21
 * Time: 下午5:02
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PermissionRequest extends Request
{
    public function rules(Request $request)
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|unique:permissions',
                    'display_name' => 'required'
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