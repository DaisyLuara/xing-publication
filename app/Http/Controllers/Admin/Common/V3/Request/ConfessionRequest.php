<?php

namespace App\Http\Controllers\Admin\Common\V3\Request;

use App\Http\Requests\Request;

class ConfessionRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
                return [
                    'sign' => 'required|string',
                ];
                break;
            case 'POST':
                return [
                    'sign' => 'required|string',
                    'name' => 'required|string',
                    'phone' => 'required|regex:/^1[3456789]\d{9}$/',
                    'message' => 'required|string',
                    'media_id' => 'required|integer|exists:media,id',
                ];
                break;
            default:
                return [];
        }
    }
}

