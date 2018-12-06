<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/4
 * Time: 下午2:02
 */

namespace App\Http\Controllers\Admin\Team\V1\Request;


use Dingo\Api\Http\FormRequest;

class TeamSystemDetailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'user_id' => 'required|integer',
                    'project_name' => 'required|string',
                    'system_money' => 'required|numeric'
                ];
                break;
            case 'PATCH':
                return [
                    'user_id' => 'integer',
                    'project_name' => 'string',
                    'system_money' => 'numeric'
                ];
                break;
            default:
                return [];
        }

    }
}