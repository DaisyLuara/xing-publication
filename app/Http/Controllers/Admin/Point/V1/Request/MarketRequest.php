<?php

namespace App\Http\Controllers\Admin\Point\V1\Request;

use Dingo\Api\Http\FormRequest;

class MarketRequest extends FormRequest
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
                    'areaid' => 'required',
                    'name' => 'required',
                    'contract.type' => 'required',
                    'contract.pay' => 'required',
                    'contract.enter_sdate' => 'required|date_format:Y-m-d H:i:s',
                    'contract.enter_edate' => 'required|date_format:Y-m-d H:i:s',
                    'contract.oper_sdate' => 'required|date_format:Y-m-d H:i:s',
                    'contract.oper_edate' => 'required|date_format:Y-m-d H:i:s',
                    'contract.mode' => 'required',
                    'share.offer' => 'required',
                    'share.mad' => 'required',
                    'share.play' => 'required',
                    'share.qrcode' => 'required',
                    'share.wx_pa' => 'required',
                    'share.wx_mp' => 'required',
                    'share.app' => 'required',
                    'share.phone' => 'required',
                    'share.coupon' => 'required',
                ];
                break;
            default:
                return [];
        }
    }

}
