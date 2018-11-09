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
                    'payment_payee_id' => 'required|integer',
                    'remark' => 'string|nullable|max:150'
                ];
                break;
            case 'PATCH':
                return [
                    'contract_id' => 'integer',
                    'applicant' => 'integer',
                    'amount' => 'string',
                    'type' => Rule::in([1, 2, 3]),
                    'reason' => 'string:max:150',
                    'payment_payee_id' => 'integer',
                    'remark' => 'string|nullable|max:150'
                ];
                break;
            default:
                return [];
        }
    }
}
