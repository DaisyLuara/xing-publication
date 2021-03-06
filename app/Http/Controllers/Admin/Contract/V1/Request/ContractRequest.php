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
                    'product_status' => Rule::in([0, 1, 2]),
                    'remark' => 'string|nullable|max:1000',
                    'amount' => 'numeric',
                    'special_num' => 'required|integer',
                    'common_num' => 'required|integer|max:2',
                    'contract_number' => 'filled|unique:contracts'
                ];
                break;
            case 'PATCH':
                return [
                    'special_num' => 'required|integer',
                    'common_num' => 'required|integer'
                ];
                break;
            default:
                return [];
        }

    }
}

