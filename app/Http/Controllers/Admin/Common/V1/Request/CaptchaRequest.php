<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;

class CaptchaRequest extends Request
{
    public function rules()
    {
        return [
            'phone' => 'required|regex:/^1[3456789]\d{9}$/',
        ];
    }
}
