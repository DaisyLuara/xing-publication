<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/12
 * Time: 下午2:35
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Request;


use App\Http\Requests\Request;

class InvoiceReceiptRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'receipt_company' => 'required|max:50',
                    'receipt_money' => 'required',
                    'receipt_date' => 'required',
                ];
                break;
            case 'PATCH':
                return [
                    'receipt_company' => 'string|max:50',
                    'receipt_money' => 'string',
                    'receipt_date' => 'string',
                ];
                break;
            default:
                return [];
        }
    }
}