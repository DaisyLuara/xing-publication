<?php

namespace App\Http\Controllers\Admin\Point\V1\Request;

use Dingo\Api\Http\FormRequest;

class PointRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
                return [
                    'lat' => 'required',
                    'lng' => 'required',
                ];
                break;
            default:
                return [];
        }
    }


}