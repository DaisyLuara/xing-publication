<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Request;

use App\Http\Requests\Request;

class AttributeRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string',
                    'desc' => 'required|string',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'required|string',
                    'desc' => 'required|string'
                ];
                break;
            default:
                return [];
        }
    }

}