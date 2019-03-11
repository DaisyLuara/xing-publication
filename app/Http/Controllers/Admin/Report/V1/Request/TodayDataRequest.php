<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/3/6
 * Time: 下午3:27
 */

namespace App\Http\Controllers\Admin\Report\V1\Request;


use App\Http\Requests\Request;

class TodayDataRequest extends Request
{
    public function rules()
    {
        return [
            'id' => 'required|integer',
            'belong' => 'filled',
        ];
    }
}