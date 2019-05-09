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
                    'sign' => 'required_without:z|string',
                    'z' => 'required_without:sign|string',
                    'phone' => 'string|regex:/^1[3456789]\d{9}$/',
                    'qiniu_id'=>'required_with:z|integer'
                ];
                break;
            case 'POST':
                return [
                    'sign' => 'required_without:z|string',
                    'z' => 'required_without:sign|string',
                    'name' => 'required|string',
                    'phone' => 'required_with:sign|string|regex:/^1[3456789]\d{9}$/',
                    'qiniu_id' => 'required_with:z|integer',
                    'message' => 'required|string',
                    'media_id' => 'required_with:sign|integer',
                ];
                break;
            default:
                return [];
        }
    }
}

