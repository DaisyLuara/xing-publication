<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;

class TempCustomerRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|between:2,25',
            'mobile' => 'required|regex:/^1[3456789]\d{9}$/|unique:temp_customers',
        ];
    }


    public function messages()
    {
        return [
            'mobile.required' => '手机号码必须输入',
            'mobile.unique' => '手机号码被占用',
            'mobile.regex' => '手机号码不合法',
        ];
    }
}
