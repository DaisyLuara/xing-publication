<?php

namespace App\Http\Controllers\Admin\User\V1\Request;

use Dingo\Api\Http\FormRequest;

class UserRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|between:2,25',
                    'password' => 'required|string|min:6',
                    'phone' => 'required|regex:/^1[3456789]\d{9}$/|unique:users',
                    'role_id' => 'required'
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'between:2,25|string',
                    'phone' => 'regex:/^1[3456789]\d{9}$/',
                    'password' => 'string|min:6'
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'verification_key' => '短信验证码 key',
            'verification_code' => '短信验证码',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.between' => '用户名必须介于 2 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
            'password.required' => '密码必须输入',
            'password.string' => '密码必须是字符串',
            'password.min' => '密码最少6位',
            'phone.required' => '手机号码必须输入',
            'phone.unique' => '手机号码被占用',
            'role_id.required' => '用户必须分配角色',
        ];
    }
}
