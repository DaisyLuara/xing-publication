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
                    'password' => 'filled',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'filled|string',
                    'phone' => 'filled|regex:/^1[3456789]\d{9}$/',
                    'password' => 'filled',
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