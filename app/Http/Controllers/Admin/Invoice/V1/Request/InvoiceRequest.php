<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                    'taxpayer_num' => 'required|alpha_num',
                    'phone' => 'required|regex:/^1[3456789]\d{9}$/',
                    'address' => 'required|string|max:100',
                    'account_bank' => 'required|string',
                    'account_number' => 'required|alpha_num',
                    'receive_status' => Rule::in([0, 1]),
                    'kind' => 'required|string|max:50',
                    'invoice_content.*.num' => 'required|integer',
                    'invoice_content.*.price' => 'required|numeric',
                    'invoice_content.*.money' => 'required|numeric',
                    'total' => 'required|numeric',
                    'total_text' => 'required|string',
                    'remark'=>'required|string|max:150'
                ];
                break;
            case 'PATCH':
                return [
                    'contract_id' => 'required|integer',
                    'applicant' => 'required|integer',
                    'type' => Rule::in([0, 1]),
                    'taxpayer_num' => 'required|alpha_num',
                    'phone' => 'required|regex:/^1[3456789]\d{9}$/',
                    'address' => 'required|string|max:100',
                    'account_bank' => 'required|string',
                    'account_number' => 'required|alpha_num',
                    'receive_status' => Rule::in([0, 1]),
                    'kind' => 'required|string|max:50',
                    'invoice_content.*.num' => 'required|integer',
                    'invoice_content.*.price' => 'required|numeric',
                    'invoice_content.*.money' => 'required|numeric',
                    'total' => 'required|numeric',
                    'total_text' => 'required|string',
                    'remark'=>'required|string|max:150'
                ];
                break;
            default:
                return [];
        }
    }
}
