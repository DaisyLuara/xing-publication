<?php

namespace App\Http\Controllers\Admin\Auth\V1\Request;

use App\Http\Requests\Request;

class AuthorizationRequest extends Request
{

    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ];
    }
}
