<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/30
 * Time: 上午10:12
 */

namespace App\Http\Controllers\Admin\Team\V1\Request;


use App\Http\Requests\Request;

class TeamSystemRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'applicant' => 'required|integer',
                    'name' => 'required',
                    'remark' => 'required|max:150'
                ];
                break;
            case 'PATCH':
                return [
                ];
                break;
            default:
                return [];
        }

    }
}