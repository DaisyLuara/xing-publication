<?php

namespace App\Http\Requests\Api\V1;

use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => ['required', Rule::in(['marketing', 'point', 'project'])]
        ];
    }
}
