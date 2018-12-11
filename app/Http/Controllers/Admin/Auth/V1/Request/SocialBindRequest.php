<?php

namespace App\Http\Controllers\Admin\Auth\V1\Request;

use App\Http\Requests\Request;

class SocialBindRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'verification_key' => 'required|string',
            'verification_code' => 'required|string',
            'openid' => 'required|string',
        ];
    }

}
