<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Device\V1\Request;

use App\Http\Requests\Request;

class FeedBackRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'action' => 'filled',
            'device_code' => 'filled',
            'start_date' => 'filled|date_format:Y-m-d H:i:s',
            'end_date' => 'filled|date_format:Y-m-d H:i:s',
        ];
    }
}
