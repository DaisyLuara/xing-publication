<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/29
 * Time: 上午10:52
 */

namespace App\Http\Controllers\Admin\Team\V1\Request;


use App\Http\Requests\Request;

class TeamRateRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [

                ];
                break;
            case 'PATCH':
                return [
                    'interaction' => 'numeric',
                    'originality' => 'numeric',
                    'h5_1' => 'numeric',
                    'h5_2' => 'numeric',
                    'animation' => 'numeric',
                    'plan' => 'numeric',
                    'tester' => 'numeric',
                    'operation' => 'numeric'
                ];
                break;
            default:
                return [];
        }

    }
}