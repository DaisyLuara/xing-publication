<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 下午2:28
 */

namespace App\Http\Controllers\Admin\Payment\V1\Request;


use Dingo\Api\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentPayeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|max:50|unique:payment_payees',
                    'account_bank' => 'required|string',
                    'account_number' => 'required|alpha_num',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => Rule::unique('payment_payees')->ignore($request->id),
                    'account_bank' => 'string',
                    'account_number' => 'alpha_num',
                ];
                break;
            default:
                return [];
        }
    }
}