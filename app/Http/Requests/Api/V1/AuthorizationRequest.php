<?php

namespace App\Http\Requests\Api\V1;

use Dingo\Api\Http\FormRequest;

class AuthorizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
            'verification_key' => 'required|string',
            'verification_code' => 'required|string',
        ];
    }
}
