<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Request;

use Illuminate\Validation\Rule;
use Dingo\Api\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
                    'type' => Rule::in([0, 1]),
                    'invoice_company_id' => 'required|integer',
                    'receive_status' => Rule::in([0, 1]),
                    'invoice_content.*.num' => 'required|integer',
                    'invoice_content.*.price' => 'required|numeric',
                    'invoice_content.*.money' => 'required|numeric',
                    'total' => 'required|numeric',
                    'total_text' => 'required|string',
                    'remark' => 'string|nullable|max:150'
                ];
                break;
            case 'PATCH':
                return [
                    'contract_id' => 'integer',
                    'applicant' => 'integer',
                    'type' => Rule::in([0, 1]),
                    'invoice_company_id' => 'integer',
                    'receive_status' => Rule::in([0, 1]),
                    'invoice_content.*.num' => 'integer',
                    'invoice_content.*.price' => 'numeric',
                    'invoice_content.*.money' => 'numeric',
                    'total' => 'numeric',
                    'total_text' => 'string',
                    'remark' => 'string|nullable|max:150'
                ];
                break;
            default:
                return [];
        }
    }
}
