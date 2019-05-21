<?php

namespace App\Http\Controllers\Admin\Company\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CompanyRequest extends Request
{

    public function rules(): ?array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string',
                    'address' => 'required|string',
                    'category' => Rule::in([0, 1]),
                    'parent_id' => 'filled',
                    'description' => 'required',
                    'logo_media_id' => 'required|integer',
                    'internal_name' => 'required|string',
                    'bd_user_id' => 'required',
                    'customer_name' => 'required_if:category,0',
                    'position' => 'required_if:category,0',
                    'phone' => 'required_if:category,0|regex:/^1[3456789]\d{9}$/|unique:customers',
                    'telephone' => 'filled',
                    'password' => 'required_if:category,0|string|min:8',
                    'role_id' => 'required_if:category,0|integer'
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'filled',
                    'address' => 'filled',
                    'category' => 'filled',
                    'parent_id' => 'filled',
                    'description' => 'filled',
                    'logo_media_id' => 'filled',
                    'internal_name' => 'filled',
                    'bd_user_id' => 'filled',
                    'status' => 'filled'
                ];
                break;
        }
    }

    public function attributes(): array
    {
        return [
            'name' => '公司全称',
            'address' => '公司地址',
            'category' => '公司属性',
            'description' => '公司详情',
            'logo_media_id' => '公司logo',
            'internal_name' => '公司简称',
            'bd_user_id' => '所属人',

        ];
    }
}