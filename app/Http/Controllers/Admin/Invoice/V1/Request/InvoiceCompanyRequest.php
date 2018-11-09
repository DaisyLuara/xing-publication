<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 上午10:11
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Request;


use Dingo\Api\Http\FormRequest;

class InvoiceCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string',
                    'taxpayer_num' => 'required|alpha_num',
                    'phone' => 'nullable|regex:/^1[3456789]\d{9}$/',
                    'telephone' => 'nullable',
                    'address' => 'required|string|max:100',
                    'account_bank' => 'required|string',
                    'account_number' => 'required|alpha_num',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'string',
                    'taxpayer_num' => 'alpha_num',
                    'phone' => 'nullable|regex:/^1[3456789]\d{9}$/',
                    'telephone' => 'nullable',
                    'address' => 'string|max:100',
                    'account_bank' => 'string',
                    'account_number' => 'alpha_num',
                ];
                break;
            default:
                return [];
        }
    }
}