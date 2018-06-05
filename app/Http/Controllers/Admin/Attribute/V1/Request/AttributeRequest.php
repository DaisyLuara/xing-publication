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

    }

}