<?php

namespace App\Http\Controllers\Admin\Invoice\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class InvoiceRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $method = $this->method();
        if ($method === 'POST') {
            return [
                'contract_id' => 'required|integer',
                'applicant' => 'required|integer',
                'type' => Rule::in([0, 1]),
                'invoice_company_id' => 'required|integer',
                'invoice_content.*.invoice_kind_id' => 'required|integer',
                'invoice_content.*.goods_service_id' => 'required|integer',
                'invoice_content.*.num' => 'required|integer',
                'invoice_content.*.price' => 'required|numeric',
                'invoice_content.*.money' => 'required|numeric',
                'total' => 'required|numeric',
                'total_text' => 'required|string',
                'remark' => 'string|nullable|max:1000'
            ];
        }
        return [];
    }

    public function attributes(): array
    {
        return [
            'contract_id' => '合同',
            'applicant' => '申请人',
            'type' => '开票类型',
            'invoice_company_id' => '开票公司',
            'invoice_content.*.invoice_kind_id' => '开票种类',
            'invoice_content.*.goods_service_id' => '服务名称',
            'invoice_content.*.num' => '数量',
            'invoice_content.*.price' => '单价',
            'invoice_content.*.money' => '金额',
            'total' => '总计',
        ];
    }
}
