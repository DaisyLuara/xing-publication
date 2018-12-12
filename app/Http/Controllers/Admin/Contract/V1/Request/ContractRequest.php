<?php

namespace App\Http\Controllers\Admin\Contract\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ContractRequest extends Request
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
                    'name' => 'required|max:50',
                    'type' => Rule::in([0, 1, 2]),
                    'receive_date' => 'string|nullable',
                    'ids' => 'string',
                    'remark' => 'string|nullable|max:1000',
                    'amount' => 'string'
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'max:50',
                    'type' => Rule::in([0, 1, 2]),
                    'receive_date' => 'string|nullable',
                    'ids' => 'string',
                    'remark' => 'string|nullable|max:1000'
                ];
                break;
            default:
                return [];
        }

    }
}
