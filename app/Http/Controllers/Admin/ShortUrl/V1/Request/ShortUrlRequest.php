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
            case 'post':
                return [
                    'adid' => 'required',
                ];
                break;
            default:
                return [];
        }
    }


}
