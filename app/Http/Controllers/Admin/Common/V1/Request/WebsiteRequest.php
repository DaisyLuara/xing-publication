<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/15
 * Time: 下午3:45
 */

namespace App\Http\Controllers\Admin\Common\V1\Request;


use App\Http\Requests\Request;

class WebsiteRequest extends Request
{

    public function rules()
    {
        return [
            'name' => 'required|string',
            'contact' => 'required|string',
            'remark' => 'string|max:150',
            'type' => 'required|in:1,2,3'
        ];
    }
}