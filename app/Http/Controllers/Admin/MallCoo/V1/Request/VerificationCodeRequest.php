<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Request;

use App\Http\Requests\Request;

class VerificationCodeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|regex:/^1[3456789]\d{9}$/',
        ];
    }

    public function attributes()
    {
        return [
            'phone' => '手机号码',
        ];
    }

}
