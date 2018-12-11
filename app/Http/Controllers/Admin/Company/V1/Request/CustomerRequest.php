<?php

namespace App\Http\Controllers\Admin\Company\V1\Request;

use App\Http\Requests\Request;

class CustomerRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string',
                    'phone' => 'required|regex:/^1[3456789]\d{9}$/|unique:customers',
                    'telephone' => 'filled',
                    'password' => 'filled',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'filled|string',
                    'phone' => 'filled|regex:/^1[3456789]\d{9}$/',
                    'telephone' => 'filled',
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

    public function messages()

    {
        return [
            'phone.unique' => '手机号码被占用',
        ];
    }
}