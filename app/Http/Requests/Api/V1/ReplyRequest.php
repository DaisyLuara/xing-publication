<?php

namespace App\Http\Requests\Api\V1;

use Dingo\Api\Http\FormRequest;

class ReplyRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required|min:2'
        ];
    }
}
