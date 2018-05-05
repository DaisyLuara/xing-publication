<?php

namespace App\Http\Requests\Api\V1;

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
                    'address' => 'required|string',
                    'phone' => 'required|regex:/^1[3456789]\d{9}$/|unique:customers',
                    'customer_name' => 'required|string|between:2,25'
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'string',
                    'address' => 'string',
                    'phone' => 'regex:/^1[3456789]\d{9}$/',
                    'customer_name' => 'between:2,25|string'
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'name' => '公司全称',
            'address' => '公司地址',
            'phone' => '手机号码',
            'customer_name' => '客户名称'
        ];
    }
}