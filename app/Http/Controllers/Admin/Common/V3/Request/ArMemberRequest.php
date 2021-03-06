<?php

namespace App\Http\Controllers\Admin\Common\V3\Request;

use App\Http\Requests\Request;

class ArMemberRequest extends Request
{

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
                    'phone' => 'required|regex:/^1[3456789]\d{9}$/',
                ];
                break;
            case 'PATCH':
                return [
                    'z' => 'required_without:sign|string',
                    'sign' => 'required_without:z|string',
                    'verification_key' => 'required|string',
                    'verification_code' => 'required|string',
                ];
                break;
            default:
                return [];
        }
    }
}

