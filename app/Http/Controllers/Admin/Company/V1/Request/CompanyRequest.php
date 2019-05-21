<?php

namespace App\Http\Controllers\Admin\Company\V1\Request;

use App\Http\Requests\Request;

class CompanyRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string',
                    'logo_media_id' => 'required|integer',
                    'address' => 'required|string',
                    'customer_name' => 'filled',
                    'position' => 'filled',
                    'phone' => 'filled|regex:/^1[3456789]\d{9}$/|unique:customers',
                    'telephone' => 'filled',
                    'password' => 'filled|string|min:8',
                    'role_id' => 'filled'
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'string',
                    'logo_media_id' => 'integer',
                    'address' => 'string',
                    'customer_name' => 'filled',
                    'position' => 'filled',
                    'phone' => 'filled|regex:/^1[3456789]\d{9}$/',
                    'telephone' => 'filled',
                    'password' => 'filled|string|min:8',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'name' => '公司全称',
            'address' => '公司地址',
            'internal_name' => '公司简称',
            'logo_media_id' => '公司logo'
        ];
    }
}