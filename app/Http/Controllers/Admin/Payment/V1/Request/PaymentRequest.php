<?php

namespace App\Http\Controllers\Admin\Payment\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PaymentRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): ?array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'contract_id' => 'required|integer',
                    'amount' => 'required|string',
                    'type' => Rule::in([1, 2, 3]),
                    'reason' => 'required|string:max:150',
                    'payment_payee_id' => 'required|integer',
                    'remark' => 'string|nullable|max:1000'
                ];
                break;
            case 'PATCH':
                return [
                    'contract_id' => 'integer',
                    'amount' => 'string',
                    'type' => Rule::in([1, 2, 3]),
                    'reason' => 'string:max:150',
                    'payment_payee_id' => 'integer',
                    'remark' => 'string|nullable|max:1000'
                ];
                break;
            default:
                return [];
        }
    }
}
