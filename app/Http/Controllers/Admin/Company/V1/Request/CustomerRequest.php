<?php

namespace App\Http\Controllers\Admin\Company\V1\Request;

use Dingo\Api\Http\FormRequest;

class CustomerRequest extends FormRequest
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
                    'name' => 'required|string',
                    'phone' => 'required|regex:/^1[3456789]\d{9}$/|unique:customers',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'string',
                    'phone' => 'regex:/^1[3456789]\d{9}$/',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'phone' => '手机号码',
            'customer_name' => '客户名称'
        ];
    }
}