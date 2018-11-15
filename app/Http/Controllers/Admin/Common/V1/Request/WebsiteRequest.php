<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/15
 * Time: 下午3:45
 */

namespace App\Http\Controllers\Admin\Common\V1\Request;


use Dingo\Api\Http\FormRequest;

class WebsiteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'contact' => 'required|string',
            'remark' => 'string|max:150'
        ];
    }
}