<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use Dingo\Api\Http\FormRequest;

class TempCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
