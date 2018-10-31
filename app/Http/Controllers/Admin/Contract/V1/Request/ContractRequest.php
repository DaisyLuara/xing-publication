<?php

namespace App\Http\Controllers\Admin\Contract\V1\Request;

use Illuminate\Validation\Rule;
use Dingo\Api\Http\FormRequest;

class ContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|max:50',
                    'contract_number' => 'string',
                    'type' => Rule::in([0, 1]),
                    'receive_date' => 'string|nullable',
                    'ids' => 'string',
                    'remark' => 'string|nullable|max:150'
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'max:50',
                    'contract_number' => 'string',
                    'type' => Rule::in([0, 1]),
                    'receive_date' => 'string|nullable',
                    'ids' => 'string',
                    'remark' => 'string|nullable|max:150'
                ];
                break;
            default:
                return [];
        }

    }
}