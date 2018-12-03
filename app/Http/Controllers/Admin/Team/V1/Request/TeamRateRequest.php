<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/29
 * Time: 上午10:52
 */

namespace App\Http\Controllers\Admin\Team\V1\Request;


use Dingo\Api\Http\FormRequest;

class TeamRateRequest extends FormRequest
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