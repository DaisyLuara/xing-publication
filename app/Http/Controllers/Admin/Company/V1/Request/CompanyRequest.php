<?php

namespace App\Http\Controllers\Admin\Company\V1\Request;

use Dingo\Api\Http\FormRequest;

class CompanyRequest extends FormRequest
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
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'string',
                    'address' => 'string',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'name' => '公司全称',
            'address' => '公司地址',
        ];
    }
}