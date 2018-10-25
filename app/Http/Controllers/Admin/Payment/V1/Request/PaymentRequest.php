<?php

namespace App\Http\Controllers\Admin\Payment\V1\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
                    'contract_id' => 'required|integer',
                    'applicant' => 'required|integer',
                    'amount' => 'required|string',
                    'type' => Rule::in([1, 2, 3]),
                    'reason' => 'required|string:max:150',
                    'payee' => 'required|string|max:50',
                    'account_bank' => 'required|string',
                    'account_number' => 'required|alpha_num',
                    'remark' => 'required|string|max:150'
                ];
                break;
            case 'PATCH':
                return [
                    'contract_id' => 'integer',
                    'applicant' => 'integer',
                    'amount' => 'numeric',
                    'type' => Rule::in([1, 2, 3]),
                    'reason' => 'string:max:150',
                    'payee' => 'string|max:50',
                    'account_bank' => 'string',
                    'account_number' => 'alpha_num',
                    'remark' => 'string|max:150'
                ];
                break;
            default:
                return [];
        }
    }
}
