<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Request;

use Dingo\Api\Http\FormRequest;

class AttributeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

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